<?php

namespace App\Controller;

use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendFeedbackController extends AbstractController
{
    /**
     * @Route("/feedback/send", methods={"POST"}, name="send_feedback")
     */
    public function submit(Request $request, Mailer $mailer): Response
    {   
        $data = $request->request;

        $mailer->sendEmail([
            'subject' => 'Feedback',
            'htmlTemplate' => 'send_feedback/email.html.twig',
            'context' => [
                'user_type' => $this->getUser() ? 'Registered user' : 'Guest user',
                'feedback' => $data->get('feedback'),
            ]
        ]);

        return $this->render('send_feedback/success.html.twig');
    }
}
