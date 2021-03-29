<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterOrganizationController extends AbstractController
{
    /**
     * @Route("/register/organization", name="register_organization")
     */
    public function index(): Response
    {
        return $this->render('register_organization/index.html.twig');
    }

    /**
     * @Route("/register/project/create", methods={"POST"}, name="create_organization")
     */
    public function createProject(Request $request, OrganizationHandler $organizationHandler): Response
    {
        $organization = $organizationHandler->createOrganizationObjectWithRequestData($request);
    }
}
