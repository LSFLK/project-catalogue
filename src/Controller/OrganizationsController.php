<?php

namespace App\Controller;

use App\Entity\Organization;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class OrganizationsController extends AbstractController
{
    /**
     * @Route("/organizations", name="organizations")
     */
    public function index(): Response
    {
        return $this->render('organizations/index.html.twig', [
            'controller_name' => 'OrganizationsController',
        ]);
    }

    /**
     * @Route("/organizations/validate")
     */
    public function validate(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        $project = $this->getDoctrine()->getRepository(Organization::class)->findOneBy(['name' => $name]);
        
        if($project) { return new JsonResponse(false); }
        else { return new JsonResponse(true); }
    }
}
