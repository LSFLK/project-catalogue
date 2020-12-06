<?php

namespace App\Controller;

use App\Entity\DomainExpertise;
use App\Entity\TechnicalExpertise;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterProjectController extends AbstractController
{
    /**
     * @Route("/register", name="register_project")
     */
    public function index(): Response
    {
        $domain_expertise = $this->getDoctrine()->getRepository(DomainExpertise::class)->findAll();
        $technical_expertise = $this->getDoctrine()->getRepository(TechnicalExpertise::class)->findAll();

        return $this->render('register_project/index.html.twig', [
            'domain_expertise' => $domain_expertise,
            'technical_expertise' => $technical_expertise,
        ]);
    }
}
