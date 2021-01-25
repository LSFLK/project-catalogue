<?php

namespace App\Controller;

use App\Service\ProjectHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/register/review", methods={"POST"}, name="review_project")
     */
    public function reviewProject(Request $request, SessionInterface $session, ProjectHandler $projectHandler): Response
    {   
        $project_token = $this->_generateProjectToken();
        $project = $projectHandler->createProjectObject($request);
        $session->set($project_token, $project);
        
        return $this->render('review_project/index.html.twig', [
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
        $project = $session->get($project_token);

        $project->setOwner($this->getUser());
        $project_id = $projectHandler->writeNewProjectData($project);

        if($project_id) {
            $response = new RedirectResponse('/register/success', 301);
            $response->headers->set('Project-Token', $project_token);
            $response->headers->set('Project-Id', $project_id);
            return $response;
        }
        else {
            return new Response((string) "Something went wrong!");
        }
    }

    /**
     * @Route("/register/success", name="register_project_success")
     */
    public function success(Request $request, SessionInterface $session): Response
    {
        $project_token = $request->headers->get('Project-Token');
        $project_id = $request->headers->get('Project-Id');

        if(!$project_token || !$session->get($project_token) || !$project_id) {
            return $this->redirect('/', 301);
        }

        $session->remove($project_token);

        return $this->render('register_project/success.html.twig', [
            'project_id' => $project_id
        ]);
    }

    private function _generateProjectToken(): string
    {
        return bin2hex(random_bytes(20));
    }
}
