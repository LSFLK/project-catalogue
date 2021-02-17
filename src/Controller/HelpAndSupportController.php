<?php

namespace App\Controller;

use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelpAndSupportController extends AbstractController
{
    /**
     * @Route("/help", name="help_and_support")
     */
    public function index(): Response
    {
        return $this->render('help_and_support/index.html.twig');
    }

    /**
     * @Route("/help/submit", methods={"POST"}, name="submit_help_and_support")
     */
    public function submit(Request $request, Mailer $mailer): Response
    {   
        $data = $request->request;

        $mailer->sendEmail([
            'replyTo' => $data->get('email'),
            'subject' => $data->get('subject'),
            'htmlTemplate' => 'help_and_support/email.html.twig',
            'context' => [
                'name' => $data->get('name'),
                'replyTo' => $data->get('email'),
                'user_type' => $this->getUser() ? 'Registered user' : 'Guest user',
                'subject' => $data->get('subject'),
                'description' => $data->get('description'),
            ]
        ]);

        return $this->render('help_and_support/index.html.twig');
    }
}
