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
use App\Service\FileHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;

class ProjectHandler
{
    private $entityManager;
    private $fileHandler;
    private $validator;


    public function __construct(EntityManagerInterface $entityManager, FileHandler $fileHandler, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->fileHandler = $fileHandler;
        $this->validator = $validator;
    }


    public function createProjectObjectWithRequestData(Request $request): Project
    {
        $domainExpertiseRepository = $this->entityManager->getRepository(DomainExpertise::class);
        $technicalExpertiseRepository = $this->entityManager->getRepository(TechnicalExpertise::class);
        $programmingLanguageRepository = $this->entityManager->getRepository(ProgrammingLanguage::class);
        $topicRepository = $this->entityManager->getRepository(Topic::class);

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

        $domainExpertise = $domainExpertiseRepository->findOneBy(['name' => $data->get('domain_expertise')]);
        $project->setDomainExpertise($domainExpertise);

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
            $programmingLanguage = $programmingLanguageRepository->findOneBy(['name' => $language]);

            if(!$programmingLanguage) {
                $programmingLanguage = new ProgrammingLanguage();
                $programmingLanguage->setName($language);
            }

            $project->addProgrammingLanguage($programmingLanguage);
        }

        foreach ($topics as $topic) {
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
            $project->setProjectDataFile($this->fileHandler->upload($project_data_file));
        }
        
        if ($project_logo = $files ? $files->get('project_logo') : null) {
            $project->setProjectLogo($this->fileHandler->upload($project_logo));
        }
        else if($avatar_url) {
            $project->setProjectLogo($avatar_url);
        }

        return $project;
    }


    public function writeNewProject(Project $data)
    {
        $this->_validate($data);

        $project = new Project();
        $project = $this->_persistAndFlush($project, $data);
        $this->_moveProjectFilesToConfirmedDirectory($project);

        return $project->getId();
    }


    public function saveChangesMadeInProject($id, $data)
    {
        $this->_validate($data);

        $project = $this->entityManager->getRepository(Project::class)->find($id);
        $this->_removeUnwantedFilesFromConfirmedDirectory($project);

        $project = $this->_persistAndFlush($project, $data);
        $this->_moveProjectFilesToConfirmedDirectory($project);

        return $project->getId();
    }


    private function _persistAndFlush(Project $project, Project $data): Project
    {
        $domainExpertiseRepository = $this->entityManager->getRepository(DomainExpertise::class);
        $technicalExpertiseRepository = $this->entityManager->getRepository(TechnicalExpertise::class);
        $programmingLanguageRepository = $this->entityManager->getRepository(ProgrammingLanguage::class);

        $project->setOwner($data->getOwner());
        $project->setName($data->getName());
        $project->setObjective($data->getObjective());
        $project->setDescription($data->getDescription());
        $project->setOrganization($data->getOrganization());
        $project->setWebsite($data->getWebsite());
        $project->setBugTracking($data->getBugTracking());
        $project->setDocumentation($data->getDocumentation());
        $project->setProjectDataFile($data->getProjectDataFile());
        $project->setProjectLogo($data->getProjectLogo());

        $domainExpertise = $domainExpertiseRepository->findOneBy(['name' => $data->getDomainExpertise()->getName()]);
        $project->setDomainExpertise($domainExpertise);

        $technicalExpertise = $technicalExpertiseRepository->findOneBy(['name' => $data->getTechnicalExpertise()->getName()]);
        $project->setTechnicalExpertise($technicalExpertise);

        $project = $this->_compareAndUpdate($project, $data, 'GitRepo', 'Url', ['Name', 'Url']);
        $project = $this->_compareAndUpdate($project, $data, 'MailingList', 'Url', ['Name', 'Url']);
        $project = $this->_compareAndUpdate($project, $data, 'MoreInfo', 'Url', ['Name', 'Url']);
        $project = $this->_compareAndUpdate($project, $data, 'ProgrammingLanguage', 'Name', ['Name']);
        $project = $this->_compareAndUpdate($project, $data, 'Topic', 'Name', ['Name']);

        $project = $this->_removeUnwantedRelations($project, $data, 'GitRepo', 'Url');
        $project = $this->_removeUnwantedRelations($project, $data, 'MailingList', 'Url');
        $project = $this->_removeUnwantedRelations($project, $data, 'MoreInfo', 'Url');
        $project = $this->_removeUnwantedRelations($project, $data, 'ProgrammingLanguage', 'Name');
        $project = $this->_removeUnwantedRelations($project, $data, 'Topic', 'Name');

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }


    private function _validate($object)
    {
        $errors = $this->validator->validate($object);
        if (count($errors)) { throw new \Exception((string) $errors); }
    }


    private function _compareAndUpdate(Project $project, Project $data, $entity, $comparator, $attributes): Project
    {
        $getter = 'get'.$entity;
        $adder = 'add'.$entity;
        $comparatorValueGetter = 'get'.$comparator;

        foreach ($data->$getter() as $input) {
            $updated = false;
            foreach ($project->$getter() as $current) {
                if ($input->$comparatorValueGetter() === $current->$comparatorValueGetter()) {
                    foreach ($attributes as $attribute) {
                        $attributeGetter = 'get'.$attribute;
                        $attributeSetter = 'set'.$attribute;
                        $current->$attributeSetter($input->$attributeGetter());
                    }
                    $updated = true;
                    break;
                }
            }
            if (!$updated) {
                $this->_validate($input);
                $project->$adder($input);
            }
        }
        return $project;
    }


    private function _removeUnwantedRelations(Project $project, Project $data, $entity, $comparator): Project
    {
        $getter = 'get'.$entity;
        $remover = 'remove'.$entity;
        $comparatorValueGetter = 'get'.$comparator;

        foreach ($project->$getter() as $object) {
            $found = false;
            foreach ($data->$getter() as $input) {
                if ($object->$comparatorValueGetter() === $input->$comparatorValueGetter()) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $project->$remover($object);
                $this->entityManager->remove($object);
            }
        }
        return $project;
    }


    private function _moveProjectFilesToConfirmedDirectory(Project $project)
    {
        if($projectDataFile = $project->getProjectDataFile()) {
            $this->fileHandler->moveFileToConfirmedDirectory($projectDataFile);
        }

        if(($projectLogo = $project->getProjectLogo()) && (!filter_var($projectLogo, FILTER_VALIDATE_URL))) {
            $this->fileHandler->moveFileToConfirmedDirectory($projectLogo);
        }
    }


    private function _removeUnwantedFilesFromConfirmedDirectory(Project $project)
    {
        if($projectDataFile = $project->getProjectDataFile()) {
            $this->fileHandler->removeFileFromConfirmedDirectory($projectDataFile);
        }

        if(($projectLogo = $project->getProjectLogo()) && (!filter_var($projectLogo, FILTER_VALIDATE_URL))) {
            $this->fileHandler->removeFileFromConfirmedDirectory($projectLogo);
        }
    }
}