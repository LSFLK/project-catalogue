<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\DomainExpertise;
use App\Entity\TechnicalExpertise;
use App\Entity\ProgrammingLanguage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class MyProjectsController extends AbstractController
{
    /**
     * @Route("/authuser/projects", name="my_projects")
     */
    public function index(): Response
    {
        $projects = $this->getUser()->getProjects();
        $domain_expertise = $this->getDoctrine()->getRepository(DomainExpertise::class)->findAllOrderByName();
        $technical_expertise = $this->getDoctrine()->getRepository(TechnicalExpertise::class)->findAllOrderByName();
        $programming_language = $this->getDoctrine()->getRepository(ProgrammingLanguage::class)->findAllOrderByName();

        return $this->render('my_projects/index.html.twig', [
            'domain_expertise' => $domain_expertise,
            'technical_expertise' => $technical_expertise,
            'programming_language' => $programming_language,
            'projects' => $projects,
        ]);
    }
}
