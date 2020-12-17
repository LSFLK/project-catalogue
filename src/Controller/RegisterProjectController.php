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
            'website' => $request->request->get('website'),
            'objective' => $request->request->get('objective'),
            'description' => $request->request->get('description'),
            'more_info' => 'Find blogs on Ballerina in community-driven Tech Blog.',
            'repository' => 'https://github.com/ballerina-platform/ballerina-lang',
            'categories' => array('programming'),
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
