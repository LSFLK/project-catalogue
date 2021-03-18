<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManageUserAccountController extends AbstractController
{
    /**
     * @Route("/authuser/profile", name="manage_user_account")
     */
    public function index(): Response
    {
        return $this->render('manage_user_account/index.html.twig');
    }
}
