<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewProjectController extends AbstractController
{
    /**
     * @Route("/projects/sample", name="view_project")
     */
    public function index(): Response
    {
        $domain_expertise = str_replace(' ', '-', strtolower('Productivity Tools'));
        $technical_expertise = str_replace(' ', '-', strtolower('Programming'));

        return $this->render('view_project/index.html.twig', [
            'name' => 'Ballerina',
            'objective' => 'An open source programming language and platform for cloud-era application programmers to easily write software that just works.',
            'description' => 'For decades, programming languages have treated networks simply as I/O sources. Ballerina introduces fundamental, new abstractions of client objects, services, resource functions, and listeners to bring networking into the language so that programmers can directly address the Fallacies of Distributed Computing as part of their application logic. This facilitates resilient, secure, performant network applications to be within every programmer’s reach.',
            'organization' => 'Open Source Lanka',
            'website' => 'ballerina.io',
            'domain_expertise' => $domain_expertise,
            'technical_expertise' => $technical_expertise,
            'git_repositories' => 'https://github.com/ballerina-platform/ballerina-lang',
            'more_info' => 'Find blogs on Ballerina in community-driven Tech Blog.',
            'languages' => array('java', 'ballerina'),
            'tags' => array('programming-language', 'language', 'compiler')
        ]);
    }
}
