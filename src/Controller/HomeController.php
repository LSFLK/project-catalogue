<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Entity\DomainExpertise;
use App\Entity\TechnicalExpertise;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $projects_count = $this->getDoctrine()->getRepository(Project::class)->getProjectsCount();
        $users_count = $this->getDoctrine()->getRepository(User::class)->getUsersCount();
        $volunteers_count = $this->getDoctrine()->getRepository(User::class)->getUsersCount();

        $domain_expertise_count = $this->getDoctrine()->getRepository(DomainExpertise::class)->getDomainExpertiseCount();
        $technical_expertise_count = $this->getDoctrine()->getRepository(TechnicalExpertise::class)->getTechnicalExpertiseCount();
        $expertise_areas_count = $domain_expertise_count + $technical_expertise_count;

        return $this->render('home/index.html.twig', [
            'projects_count' => $projects_count,
            'users_count' => $users_count,
            'volunteers_count' => $volunteers_count,
            'expertise_areas_count' => $expertise_areas_count,
        ]);
    }
}
