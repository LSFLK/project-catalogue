<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Entity\Project;
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
        $owner = $organization->getOwner();
        $projects = $owner->getProjects();
        $is_owner = $owner === $this->getUser();
        
        return $this->render('view_organization/index.html.twig', [
            'organization' => $organization,
            'projects_count' => count($projects),
            'is_owner' => $is_owner,
        ]);
    }
}
