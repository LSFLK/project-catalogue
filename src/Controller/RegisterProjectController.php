<?php

namespace App\Controller;

use App\Entity\DomainExpertise;
use App\Entity\TechnicalExpertise;
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
        $project = new \stdClass();

        $project->name        = $request->request->get('name');
        $project->objective   = $request->request->get('objective');
        $project->description = $request->request->get('description');

        $project->organization = $request->request->get('organization');
        $project->website      = $request->request->get('website');

        $project->domain_expertise    = str_replace(' ', '-', strtolower($request->request->get('domain_expertise')));
        $project->technical_expertise = str_replace(' ', '-', strtolower($request->request->get('technical_expertise')));
        
        $project->bug_tracking  = $request->request->get('bug_tracking');
        $project->documentation = $request->request->get('documentation');

        $project->git_repo_names = $request->request->get('git_repo_names');
        $project->git_repo_urls  = $request->request->get('git_repo_urls');

        $project->mailing_list_names = $request->request->get('mailing_list_names');
        $project->mailing_list_urls  = $request->request->get('mailing_list_urls');

        $project->more_info_titles = $request->request->get('more_info_titles');
        $project->more_info_urls   = $request->request->get('more_info_urls');
        
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
        $project = json_decode($project_data);

        print_r($project);
    }


    /**
     * @Route("/register", name="register_project")
     */
    public function index(): Response
    {
        $domain_expertise = include('options/domain_expertise.php');
        $technical_expertise = include('options/domain_expertise.php');

        return $this->render('register_project/index.html.twig', [
            'domain_expertise' => $domain_expertise,
            'technical_expertise' => $technical_expertise,
        ]);
    }
}
