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
     * @Route("/projects/search", name="search_projects")
     */
    public function search(Request $request): JsonResponse
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->findByRequestQueryParams($request->query);
        
        $content = [];

        foreach($projects as $project) {
            $card = $this->renderView('projects/card.html.twig', ['project' => $project]);
            array_push($content, $card);
        }

        return new JsonResponse($content);
    }

    /**
     * @Route("/projects", name="projects")
     */
    public function index(Request $request): Response
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->findByRequestQueryParams($request->query);
        $domain_expertise = $this->getDoctrine()->getRepository(DomainExpertise::class)->findAll();
        $technical_expertise = $this->getDoctrine()->getRepository(TechnicalExpertise::class)->findAll();
        $programming_language = $this->getDoctrine()->getRepository(ProgrammingLanguage::class)->findAll();       

        return $this->render('projects/index.html.twig', [
            'domain_expertise' => $domain_expertise,
            'technical_expertise' => $technical_expertise,
            'programming_language' => $programming_language,
            'projects' => $projects,
        ]);
    }
}
