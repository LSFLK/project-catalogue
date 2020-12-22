<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\DomainExpertise;
use App\Entity\TechnicalExpertise;
use App\Entity\ProgrammingLanguage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProjectsController extends AbstractController
{
    /**
     * @Route("/projects", name="projects")
     */
    public function index(): Response
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->findAll();
        $domain_expertise = $this->getDoctrine()->getRepository(DomainExpertise::class)->findAll();
        $technical_expertise = $this->getDoctrine()->getRepository(TechnicalExpertise::class)->findAll();
        $programming_language = $this->getDoctrine()->getRepository(ProgrammingLanguage::class)->findAll();

        $_projects = [];

        foreach ($projects as $project) {
            $_project = new \stdClass();
            $_project->id = $project->getId();
            $_project->name = $project->getName();
            $_project->project_logo = $project->getProjectLogo();
            $_project->objective = $project->getObjective();
            
            array_push($_projects, $_project);
        }

        return $this->render('projects/index.html.twig', [
            'domain_expertise' => $domain_expertise,
            'technical_expertise' => $technical_expertise,
            'programming_language' => $programming_language,
            'projects' => $_projects,
        ]);
    }
}
