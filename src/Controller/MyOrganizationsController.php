<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyOrganizationsController extends AbstractController
{
    /**
     * @Route("/authuser/organizations", name="my_organizations")
     */
    public function index(): Response
    {
        return $this->render('my_organizations/index.html.twig', [
            'controller_name' => 'OrganizationsController',
        ]);
    }
}
