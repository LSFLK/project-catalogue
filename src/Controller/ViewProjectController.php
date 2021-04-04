<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewProjectController extends AbstractController
{
    /**
     * @Route("/projects/id={id}", name="view_project")
     */
    public function index($id): Response
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);
        $is_owner = $project->getOwner() === $this->getUser();
        
        return $this->render('view_project/index.html.twig', [
            'project' => $project,
            'is_owner' => $is_owner,
            'dir' => $this->getParameter('public_confirmed_dir')
        ]);
    }
}
