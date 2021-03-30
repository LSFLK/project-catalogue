<?php

namespace App\Controller;

use App\Entity\Organization;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewOrganizationController extends AbstractController
{
    /**
     * @Route("/organizations/id={id}", name="view_organization")
     */
    public function index($id): Response
    {
        $organization = $this->getDoctrine()->getRepository(Organization::class)->find($id);
        $is_owner = $organization->getOwner() === $this->getUser();
        
        return $this->render('view_organization/index.html.twig', [
            'organization' => $organization,
            'is_owner' => $is_owner,
            'dir' => $this->getParameter('public_confirmed_dir')
        ]);
    }
}
