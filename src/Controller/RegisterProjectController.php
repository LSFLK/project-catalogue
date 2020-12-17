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
        return $this->render('view_project/index.html.twig', [
            'name' => $request->request->get('name'),
            'objective' => $request->request->get('objective'),
            'description' => $request->request->get('description'),
            'organization' => $request->request->get('organization'),
            'website' => $request->request->get('website'),
            'domain_expertise' => str_replace(' ', '-', strtolower($request->request->get('domain_expertise'))),
            'technical_expertise' => str_replace(' ', '-', strtolower($request->request->get('technical_expertise'))),
            'bug_tracking' => $request->request->get('bug_tracking'),
            'documentation' => $request->request->get('documentation'),
            'git_repo_names' => $request->request->get('git_repo_names'),
            'git_repo_urls' => $request->request->get('git_repo_urls'),
            'mailing_list_names' => $request->request->get('git_repo_names'),
            'mailing_list_urls' => $request->request->get('git_repo_urls'),
            'more_info_titles' => $request->request->get('more_info_titles'),
            'more_info_urls' => $request->request->get('more_info_urls'),
            'languages' => array('java', 'ballerina'),
            'tags' => array('programming-language', 'language', 'compiler')
        ]);
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
