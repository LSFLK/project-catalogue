<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelpAndSupportController extends AbstractController
{
    /**
     * @Route("/help/and/support", name="help_and_support")
     */
    public function index(): Response
    {
        return $this->render('help_and_support/index.html.twig', [
            'controller_name' => 'HelpAndSupportController',
        ]);
    }
}
