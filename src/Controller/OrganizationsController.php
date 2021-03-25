<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrganizationsController extends AbstractController
{
    /**
     * @Route("/authuser/organizations", name="organizations")
     */
    public function index(): Response
    {
        return $this->render('organizations/index.html.twig', [
            'controller_name' => 'OrganizationsController',
        ]);
    }
}
