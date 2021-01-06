<?php

namespace App\Controller;

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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Filesystem\Filesystem;

class RegisterProjectController extends AbstractController
{  
    /**
     * @Route("/register/review", methods={"POST"}, name="review_project")
     */
    public function reviewProject(Request $request, FileUploader $fileUploader): Response
    {
        $data = $request->request;
        $files = $request->files;
    
        $project = new \stdClass();

        $project->name        = $data->get('name');
        $project->objective   = $data->get('objective');
        $project->description = $data->get('description');

        $project->organization = $data->get('organization');
        $project->website      = $data->get('website');

        $project->domain_expertise    = $data->get('domain_expertise');
        $project->technical_expertise = $data->get('technical_expertise');
        
        $project->bug_tracking  = $data->get('bug_tracking');
        $project->documentation = $data->get('documentation');

        $git_repos = [];
        $languages = [];
        $topics = [];
        $git_repo_names = array_filter($data->get('git_repo_names'));
        $git_repo_urls  = array_filter($data->get('git_repo_urls'));

        for($index = 0; $index < count($git_repo_names); $index++) {
            $gitRepo = new GitRepo();
            $gitRepo->setName($git_repo_names[$index]);
            $gitRepo->setUrl($git_repo_urls[$index]);

            $_gitRepo = new GitHubAPI($gitRepo);
            array_push($git_repos, $_gitRepo->getGitRepoRequiredData());

            $languages = array_merge($languages, $_gitRepo->getLanguages());
            $topics = array_merge($topics, $_gitRepo->getTopics());
        }

        $mailing_lists = [];
        $mailing_list_names = array_filter($data->get('mailing_list_names'));
        $mailing_list_urls  = array_filter($data->get('mailing_list_urls'));

        for($index = 0; $index < count($mailing_list_names); $index++) {
            $_mailingList = [
                'name' => $mailing_list_names[$index],
                'url'  => $mailing_list_urls[$index]
            ];

            array_push($mailing_lists, $_mailingList);
        }

        $more_infos = [];
        $more_info_names = array_filter($data->get('more_info_names'));
        $more_info_urls  = array_filter($data->get('more_info_urls'));

        for($index = 0; $index < count($more_info_names); $index++) {
            $_moreInfo = [
                'name' => $more_info_names[$index],
                'url'  => $more_info_urls[$index]
            ];

            array_push($more_infos, $_moreInfo);
        }

        $project->git_repos = $git_repos;
        $project->mailing_lists  = $mailing_lists;
        $project->more_infos = $more_infos;
        
        $project->languages  = array_unique($languages);
        $project->topics = array_unique($topics);

        $project_data_file = $files ? $files->get('project_data_file') : null;
        $project->project_data_file = $project_data_file ? $fileUploader->upload($project_data_file) : null;

        $project_logo = $files ? $files->get('project_logo') : null;
        $project->project_logo = $project_logo ? $fileUploader->upload($project_logo) : null;
        
        return $this->render('view_project/index.html.twig', [
            'project' => $project,
            'project_data' => json_encode($project),
            'dir' => $this->getParameter('public_temp_dir')
        ]);
    }

    /**
     * @Route("/register/new", methods={"POST"}, name="register")
     */
    public function registerProject(Request $request, ValidatorInterface $validator): Response
    {
        $filesystem = new Filesystem();
        $temp_dir = $this->getParameter('temp_dir');
        $confirmed_dir = $this->getParameter('confirmed_dir');

        $project_data = $request->request->get('project_data');
        $data = json_decode($project_data);
        $entityManager = $this->getDoctrine()->getManager();
        $validation = true;

        $project = new Project();
        $project->setName($data->name);
        $project->setObjective($data->objective);
        $project->setDescription($data->description);
        $project->setOrganization($data->organization);
        $project->setWebsite($data->website);
        $project->setBugTracking($data->bug_tracking);
        $project->setDocumentation($data->documentation);

        $domainExpertiseRepository = $this->getDoctrine()->getRepository(DomainExpertise::class);
        $domainExpertise = $domainExpertiseRepository->findOneBy(['name' => $data->domain_expertise]);
        $project->setDomainExpertise($domainExpertise);

        $technicalExpertiseRepository = $this->getDoctrine()->getRepository(TechnicalExpertise::class);
        $technicalExpertise = $technicalExpertiseRepository->findOneBy(['name' => $data->technical_expertise]);
        $project->setTechnicalExpertise($technicalExpertise);

        foreach ($data->git_repos as $git_repo) {
            $gitRepo = new GitRepo();
            $gitRepo->setName($git_repo->name);
            $gitRepo->setUrl($git_repo->url);
            $gitRepo->setLicenseName($git_repo->licenseName);
            $gitRepo->setStarsCount($git_repo->starsCount);
            $gitRepo->setForksCount($git_repo->forksCount);
            $project->addGitRepo($gitRepo);

            $errors = $validator->validate($gitRepo);

            if (!count($errors)) { $entityManager->persist($gitRepo); }
            else { return new Response((string) $errors); }
        }

        foreach ($data->mailing_lists as $mailing_list) {
            $mailingList = new MailingList();
            $mailingList->setName($mailing_list->name);
            $mailingList->setUrl($mailing_list->url);
            $project->addMailingList($mailingList);

            $errors = $validator->validate($mailingList);

            if (!count($errors)) { $entityManager->persist($mailingList); }
            else { return new Response((string) $errors); }
        }

        foreach ($data->more_infos as $more_info) {
            $moreInfo = new MoreInfo();
            $moreInfo->setName($more_info->name);
            $moreInfo->setUrl($more_info->url);
            $project->addMoreInfo($moreInfo);

            $errors = $validator->validate($moreInfo);

            if (!count($errors)) { $entityManager->persist($moreInfo); }
            else { return new Response((string) $errors); }
        }

        $project_data_file = $data->project_data_file;
        $project_logo = $data->project_logo;

        if($project_data_file) {
            $filesystem->rename($temp_dir.$project_data_file, $confirmed_dir.$project_data_file);
            $project->setProjectDataFile($project_data_file);
        }

        if($project_logo) {
            $filesystem->rename($temp_dir.$project_logo, $confirmed_dir.$project_logo);
            $project->setProjectLogo($project_logo);
        }

        foreach ($data->languages as $language) {
            $programmingLanguageRepository = $this->getDoctrine()->getRepository(ProgrammingLanguage::class);
            $programmingLanguage = $programmingLanguageRepository->findOneBy(['name' => $language]);

            if(!$programmingLanguage) {
                $programmingLanguage = new ProgrammingLanguage();
                $programmingLanguage->setName($language);
                $entityManager->persist($programmingLanguage);
            }

            $project->addProgrammingLanguage($programmingLanguage);
        }

        foreach ($data->topics as $topic) {
            $topicRepository = $this->getDoctrine()->getRepository(Topic::class);
            $projectTopic = $topicRepository->findOneBy(['name' => $topic]);

            if(!$projectTopic) {
                $projectTopic = new Topic();
                $projectTopic->setName($topic);
                $entityManager->persist($projectTopic);
            }

            $project->addTopic($projectTopic);
        }

        $errors = $validator->validate($project);

        if (!count($errors)) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirect('/register/success/id='.$project->getId(), 301);
        }
        else {
            return new Response((string) $errors);
        }
    }

    /**
     * @Route("/register/success/id={id}", name="register_project_success")
     */
    public function success($id): Response
    {
        return $this->render('register_project/success.html.twig', [
            'project_id' => $id
        ]);
    }

    /**
     * @Route("/register", name="register_project")
     */
    public function index(): Response
    {
        $domain_expertise = $this->getDoctrine()->getRepository(DomainExpertise::class)->findAll();
        $technical_expertise = $this->getDoctrine()->getRepository(TechnicalExpertise::class)->findAll();

        return $this->render('register_project/index.html.twig', [
            'domain_expertise' => $domain_expertise,
            'technical_expertise' => $technical_expertise,
        ]);
    }
}
