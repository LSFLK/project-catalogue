<?php

namespace App\Controller;

use App\Entity\Project;
use App\Service\ProjectHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class DeleteProjectController extends AbstractController
{
    /**
     * @Route("/projects/delete", name="delete_project", methods={"POST"})
     */
    public function index(Request $request, ProjectHandler $projectHandler): Response
    {
        $project_id = $request->request->get('project_id');
        $confirm = $request->request->get('confirm');

        $project = $this->getDoctrine()->getRepository(Project::class)->find($project_id);
        $user = $this->getUser();

        if($project->getOwner() !== $user) {
            return $this->redirectToRoute('projects');
        }

        $confirm_check = $user->getEmail().'/'.str_replace(' ', '-', $project->getName());

        if($confirm_check === $confirm) {
            $projectHandler->deleteProject($project_id);
            return $this->redirectToRoute('my_projects');
        }

        return $this->redirectToRoute('view_project', ['id' => $project_id]);
    }
}
