<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Entity\Project;
use App\Entity\DomainExpertise;
use App\Entity\TechnicalExpertise;
use App\Entity\ProgrammingLanguage;
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
        $projects = $organization->getProjects();
        $is_owner = $owner === $this->getUser();
        
        return $this->render('view_organization/index.html.twig', [
            'organization' => $organization,
            'projects_count' => count($projects),
            'is_owner' => $is_owner,
        ]);
    }

    /**
     * @Route("/organizations/id={id}/projects", name="organization_projects")
     */
    public function organization_projects($id): Response
    {
        $organization = $this->getDoctrine()->getRepository(Organization::class)->find($id);
        $projects = $organization->getProjects();
        $is_owner = $organization->getOwner() === $this->getUser();
        $domain_expertise = $this->getDoctrine()->getRepository(DomainExpertise::class)->findAllOrderByName();
        $technical_expertise = $this->getDoctrine()->getRepository(TechnicalExpertise::class)->findAllOrderByName();
        $programming_language = $this->getDoctrine()->getRepository(ProgrammingLanguage::class)->findAllOrderByName();

        return $this->render('view_organization/projects.html.twig', [
            'domain_expertise' => $domain_expertise,
            'technical_expertise' => $technical_expertise,
            'programming_language' => $programming_language,
            'organization' => $organization,
            'projects' => $projects,
            'is_owner' => $is_owner,
        ]);
    }
}
