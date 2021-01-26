<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\DomainExpertise;
use App\Entity\TechnicalExpertise;
use App\Entity\GitRepo;
use App\Entity\MailingList;
use App\Entity\MoreInfo;
use App\Entity\ProgrammingLanguage;
use App\Entity\Topic;
use App\Service\GitHubAPI;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;

class ProjectHandler
{
    private $entityManager;
    private $fileUploader;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, FileUploader $fileUploader, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
        $this->validator = $validator;
    }
    
    public function createProjectObject(Request $request)
    {
        $data = $request->request;
        $files = $request->files;

        $project = new Project();
        $project->setName($data->get('name'));
        $project->setObjective($data->get('objective'));
        $project->setDescription($data->get('description'));
        $project->setOrganization($data->get('organization'));
        $project->setWebsite($data->get('website'));
        $project->setBugTracking($data->get('bug_tracking'));
        $project->setDocumentation($data->get('documentation'));

        $domainExpertiseRepository = $this->entityManager->getRepository(DomainExpertise::class);
        $domainExpertise = $domainExpertiseRepository->findOneBy(['name' => $data->get('domain_expertise')]);
        $project->setDomainExpertise($domainExpertise);

        $technicalExpertiseRepository = $this->entityManager->getRepository(TechnicalExpertise::class);
        $technicalExpertise = $technicalExpertiseRepository->findOneBy(['name' => $data->get('technical_expertise')]);
        $project->setTechnicalExpertise($technicalExpertise);

        $languages = [];
        $topics = [];
        $avatar_url = null;
        $git_repo_names = array_filter($data->get('git_repo_names'));
        $git_repo_urls  = array_filter($data->get('git_repo_urls'));

        for($index = 0; $index < count($git_repo_names); $index++) {
            $gitRepo = new GitRepo();
            $gitRepo->setName($git_repo_names[$index]);
            $gitRepo->setUrl($git_repo_urls[$index]);

            $gitHubApi = new GitHubAPI($gitRepo);
            $gitRepoData = $gitHubApi->getGitRepoRequiredData();
            $gitRepo->setLicenseName($gitRepoData['licenseName']);
            $gitRepo->setStarsCount($gitRepoData['starsCount']);
            $gitRepo->setForksCount($gitRepoData['forksCount']);
            $project->addGitRepo($gitRepo);

            $this->_validate($gitRepo);

            $languages = array_merge($languages, $gitHubApi->getLanguages());
            $topics = array_merge($topics, $gitHubApi->getTopics());
            if(!$avatar_url) { $avatar_url = $gitHubApi->getAvatarUrl(); }
        }

        $languages  = array_unique($languages);
        $topics = array_unique($topics);

        foreach ($languages as $language) {
            $programmingLanguageRepository = $this->entityManager->getRepository(ProgrammingLanguage::class);
            $programmingLanguage = $programmingLanguageRepository->findOneBy(['name' => $language]);

            if(!$programmingLanguage) {
                $programmingLanguage = new ProgrammingLanguage();
                $programmingLanguage->setName($language);
            }

            $project->addProgrammingLanguage($programmingLanguage);
        }

        foreach ($topics as $topic) {
            $topicRepository = $this->entityManager->getRepository(Topic::class);
            $projectTopic = $topicRepository->findOneBy(['name' => $topic]);

            if(!$projectTopic) {
                $projectTopic = new Topic();
                $projectTopic->setName($topic);
            }

            $project->addTopic($projectTopic);
        }

        $mailing_list_names = array_filter($data->get('mailing_list_names'));
        $mailing_list_urls  = array_filter($data->get('mailing_list_urls'));

        for($index = 0; $index < count($mailing_list_names); $index++) {
            $mailingList = new MailingList();
            $mailingList->setName($mailing_list_names[$index]);
            $mailingList->setUrl($mailing_list_urls[$index]);
            $project->addMailingList($mailingList);
            $this->_validate($mailingList);
        }

        $more_info_names = array_filter($data->get('more_info_names'));
        $more_info_urls  = array_filter($data->get('more_info_urls'));

        for($index = 0; $index < count($more_info_names); $index++) {
            $moreInfo = new MoreInfo();
            $moreInfo->setName($more_info_names[$index]);
            $moreInfo->setUrl($more_info_urls[$index]);
            $project->addMoreInfo($moreInfo);
            $this->_validate($moreInfo);
        }

        if ($project_data_file = $files ? $files->get('project_data_file') : null) {
            $project->setProjectDataFile($this->fileUploader->upload($project_data_file));
        }
        
        if ($project_logo = $files ? $files->get('project_logo') : null) {
            $project->setProjectLogo($this->fileUploader->upload($project_logo));
        }
        else if($avatar_url) {
            $project->setProjectLogo($avatar_url);
        }

        return $project;
    }

    public function writeNewProjectData(Project $project)
    {
        $this->_validate($project);

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $this->_moveProjectFilesToConfirmedDirectory($project);

        return $project->getId();
    }

    private function _moveProjectFilesToConfirmedDirectory(Project $project)
    {
        if($projectDataFile = $project->getProjectDataFile()) {
            $this->fileUploader->moveToConfirmedDirectory($projectDataFile);
        }

        if(($projectLogo = $project->getProjectLogo()) && (!filter_var($projectLogo, FILTER_VALIDATE_URL))) {
            $this->fileUploader->moveToConfirmedDirectory($projectLogo);
        }
    }

    private function _validate($object)
    {
        $errors = $this->validator->validate($object);
        if (count($errors)) { throw new \Exception((string) $errors); }
    }
}