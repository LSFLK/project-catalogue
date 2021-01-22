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
use App\Service\ProjectHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class RegisterProjectController extends AbstractController
{
    /**
     * @Route("/register", name="register_project")
     */
    public function index(): Response
    {
        $domain_expertise_options = $this->getDoctrine()->getRepository(DomainExpertise::class)->findAllOrderByName();
        $technical_expertise_options = $this->getDoctrine()->getRepository(TechnicalExpertise::class)->findAllOrderByName();
        $project = $this->getDoctrine()->getRepository(Project::class)->find(1);

        return $this->render('register_project/index.html.twig', [
            'domain_expertise_options' => $domain_expertise_options,
            'technical_expertise_options' => $technical_expertise_options,
            'project' => $project,
            'dir' => $this->getParameter('public_confirmed_dir')
        ]);
    }

    /**
     * @Route("/register/review", methods={"POST"}, name="review_project")
     */
    public function reviewProject(Request $request, ProjectHandler $projectHandler): Response
    {    
        $project = $projectHandler->createProjectObject($request);

        $session = $request->getSession();
        $session->remove('project');
        $session->set('project', $project);
        
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
        $project->setOwner($this->getUser());
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
            if (!filter_var($project_logo, FILTER_VALIDATE_URL)) {
                $filesystem->rename($temp_dir.$project_logo, $confirmed_dir.$project_logo);
            }
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

    
}
