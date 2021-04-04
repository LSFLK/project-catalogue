<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectSettingsController extends AbstractController
{
    /**
     * @Route("/projects/settings/id={id}", name="project_settings")
     */
    public function index($id): Response
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);

        if($project->getOwner() !== $this->getUser()) {
            return $this->redirectToRoute('projects');
        }

        return $this->render('project_settings/index.html.twig', [
            'project' => $project,
            'dir' => $this->getParameter('public_confirmed_dir')
        ]);
    }
}
