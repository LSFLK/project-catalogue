<?php

namespace App\Controller;

use App\Entity\DomainExpertise;
use App\Entity\TechnicalExpertise;
use App\Service\ProjectHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class RegisterProjectController extends AbstractController
{
    /**
     * @Route("/register", name="register_project")
     */
    public function index(): Response
    {
        $domain_expertise_options = $this->getDoctrine()->getRepository(DomainExpertise::class)->findAllOrderByName();
        $technical_expertise_options = $this->getDoctrine()->getRepository(TechnicalExpertise::class)->findAllOrderByName();

        return $this->render('register_project/index.html.twig', [
            'domain_expertise_options' => $domain_expertise_options,
            'technical_expertise_options' => $technical_expertise_options
        ]);
    }

    /**
     * @Route("/register/review", methods={"POST"}, name="review_project_before_register")
     */
    public function reviewProjectBeforeRegister(Request $request, SessionInterface $session, ProjectHandler $projectHandler): Response
    {   
        $project_token = bin2hex(random_bytes(20).uniqid());
        $project = $projectHandler->createProjectObject($request);
        $session->set($project_token, $project);
        
        return $this->render('register_project/review.html.twig', [
            'project_token' => $project_token,
            'dir' => $this->getParameter('public_temp_dir')
        ]);
    }

    /**
     * @Route("/register/create", methods={"POST"}, name="create_project")
     */
    public function createProject(Request $request, SessionInterface $session, ProjectHandler $projectHandler): Response
    {  
        $project_token = $request->request->get('project_token');
        $project = $session->get($project_token || '');
    
        if($project) {
            $project->setOwner($this->getUser());
            $project_id = $projectHandler->writeNewProjectData($project);
        
            if($project_id) {
                return $this->redirectToRoute('register_project_success', [
                    'token' => $project_token,
                    'id' => $project_id
                ]);
            }
            return new Response((string) "Something went wrong!");
        }
        return new Response((string) "Something went wrong!");
    }

    /**
     * @Route("/register/success", name="register_project_success")
     */
    public function success(Request $request, SessionInterface $session): Response
    {
        $project_token = $request->query->get('token');
        $project_id = $request->query->get('id');
        $project_session = $session->get($project_token || '');

        if(!$project_token || !$project_id || !$project_session || ($project_session->getId() != $project_id)) {
            return $this->redirect('/', 301);
        }

        $session->remove($project_token);

        return $this->render('register_project/success.html.twig', [
            'project_id' => $project_id
        ]);
    }
}
