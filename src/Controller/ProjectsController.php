<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\DomainExpertise;
use App\Entity\TechnicalExpertise;
use App\Entity\ProgrammingLanguage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class ProjectsController extends AbstractController
{
    /**
     * @Route("/projects", name="projects")
     */
    public function index(Request $request): Response
    {
        if ($user_id = $request->query->get('user')) {
            return $this->redirectToRoute('my_projects');
        }

        $projects = $this->getDoctrine()->getRepository(Project::class)->findByRequestQueryParams($request->query);
        $domain_expertise = $this->getDoctrine()->getRepository(DomainExpertise::class)->findAllOrderByName();
        $technical_expertise = $this->getDoctrine()->getRepository(TechnicalExpertise::class)->findAllOrderByName();
        $programming_language = $this->getDoctrine()->getRepository(ProgrammingLanguage::class)->findAllOrderByName();       

        return $this->render('projects/index.html.twig', [
            'domain_expertise' => $domain_expertise,
            'technical_expertise' => $technical_expertise,
            'programming_language' => $programming_language,
            'projects' => $projects,
        ]);
    }

    /**
     * @Route("/projects/search")
     * @Route("/authuser/projects/search")
     */
    public function search(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        $projects = $this->getDoctrine()->getRepository(Project::class)->searchByProjectName($name);
        $content = $this->_getContentForRetrievedProjects($projects);
        return new JsonResponse($content);
    }

    /**
     * @Route("/projects/filter")
     * @Route("/authuser/projects/filter")
     */
    public function filter(Request $request): JsonResponse
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->findByRequestQueryParams($request->query);
        $content = $this->_getContentForRetrievedProjects($projects);
        return new JsonResponse($content);
    }

    private function _getContentForRetrievedProjects($projects): array
    {
        $content = [];

        foreach($projects as $project) {
            $card = $this->renderView('projects/card.html.twig', ['project' => $project]);
            array_push($content, $card);
        }

        return $content;
    }
}
