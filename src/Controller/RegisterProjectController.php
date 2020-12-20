<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\DomainExpertise;
use App\Entity\TechnicalExpertise;
use App\Entity\GitRepo;
use App\Entity\MailingList;
use App\Entity\MoreInfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterProjectController extends AbstractController
{  
    /**
     * @Route("/register/review", methods={"POST"}, name="review_project")
     */
    public function reviewProject(Request $request): Response
    {
        $data = $request->request;
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

        $project->git_repo_names = $data->get('git_repo_names');
        $project->git_repo_urls  = $data->get('git_repo_urls');

        $project->mailing_list_names = $data->get('mailing_list_names');
        $project->mailing_list_urls  = $data->get('mailing_list_urls');

        $project->more_info_names = $data->get('more_info_names');
        $project->more_info_urls  = $data->get('more_info_urls');

        $project->languages = array('java', 'ballerina');
        $project->tags = array('programming-language', 'language', 'compiler');
        
        return $this->render('view_project/index.html.twig', [
            'project' => $project,
            'project_data' => json_encode($project),
            'mode' => 'review'
        ]);
    }

    /**
     * @Route("/register/new", methods={"POST"}, name="register")
     */
    public function registerProject(Request $request): Response
    {
        $project_data = $request->request->get('project_data');
        $data = json_decode($project_data);
        $entityManager = $this->getDoctrine()->getManager();

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

        for($index = 0; $index < count($data->git_repo_names); $index++) {
            $gitRepo = new GitRepo();
            $gitRepo->setName($data->git_repo_names[$index]);
            $gitRepo->setUrl($data->git_repo_urls[$index]);
            $project->addGitRepo($gitRepo);
            $entityManager->persist($gitRepo);
        }

        for($index = 0; $index < count($data->mailing_list_names); $index++) {
            $mailingList = new MailingList();
            $mailingList->setName($data->mailing_list_names[$index]);
            $mailingList->setUrl($data->mailing_list_urls[$index]);
            $project->addMailingList($mailingList);
            $entityManager->persist($mailingList);
        }

        for($index = 0; $index < count($data->more_info_names); $index++) {
            $moreInfo = new MoreInfo();
            $moreInfo->setName($data->more_info_names[$index]);
            $moreInfo->setUrl($data->more_info_urls[$index]);
            $project->addMoreInfo($moreInfo);
            $entityManager->persist($moreInfo);
        }

        $entityManager->persist($project);
        $entityManager->flush();

        return new Response(
            'Done'
        );
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
