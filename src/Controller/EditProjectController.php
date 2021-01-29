<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\DomainExpertise;
use App\Entity\TechnicalExpertise;
use App\Service\ProjectHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class EditProjectController extends AbstractController
{
    /**
     * @Route("/projects/edit/id={id}", name="edit_project")
     */
    public function index($id): Response
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);

        if($project->getOwner() !== $this->getUser()) {
            return $this->redirectToRoute('projects');
        }

        $domain_expertise_options = $this->getDoctrine()->getRepository(DomainExpertise::class)->findAllOrderByName();
        $technical_expertise_options = $this->getDoctrine()->getRepository(TechnicalExpertise::class)->findAllOrderByName();
        
        return $this->render('edit_project/index.html.twig', [
            'project' => $project,
            'domain_expertise_options' => $domain_expertise_options,
            'technical_expertise_options' => $technical_expertise_options,
            'dir' => $this->getParameter('public_confirmed_dir')
        ]);
    }

    /**
     * @Route("/projects/edit/review/id={id}", methods={"POST"}, name="review_project_before_edit")
     */
    public function reviewProjectBeforeEdit($id, Request $request, SessionInterface $session, ProjectHandler $projectHandler): Response
    {   
        $project_token = bin2hex(random_bytes(20).uniqid());
        $project = $projectHandler->createProjectObject($request);
        $session->set($project_token, $project);
        
        return $this->render('edit_project/review.html.twig', [
            'project_token' => $project_token,
            'id' => $id,
            'dir' => $this->getParameter('public_confirmed_dir')
        ]);
    }

    /**
     * @Route("/projects/edit/update/id={id}", methods={"POST"}, name="update_project")
     */
    public function updateProject($id, Request $request, SessionInterface $session, ProjectHandler $projectHandler): Response
    {
        
    }
}
