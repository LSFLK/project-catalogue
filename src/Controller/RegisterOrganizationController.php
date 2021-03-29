<?php

namespace App\Controller;

use App\Service\OrganizationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class RegisterOrganizationController extends AbstractController
{
    /**
     * @Route("/register/organization", name="register_organization")
     */
    public function index(): Response
    {
        return $this->render('register_organization/index.html.twig');
    }

    /**
     * @Route("/register/organization/create", methods={"POST"}, name="create_organization")
     */
    public function createOrganization(Request $request, OrganizationHandler $organizationHandler): Response
    {
        $organization_token = bin2hex(random_bytes(20).uniqid());
        $organization = $organizationHandler->createOrganizationObjectWithRequestData($request);

        if($organization) {
            $organization->setOwner($this->getUser());
            $organization_id = $organizationHandler->writeNewOrganization($organization);
        
            if($organization_id) {
                return $this->redirectToRoute('register_organization_success', [
                    'token' => $organization_token,
                    'id' => $organization_id
                ]);
            }
            return new Response((string) "Something went wrong!");
        }
        return new Response((string) "Something went wrong!");
    }

    /**
     * @Route("/register/organization/success", name="register_organization_success")
     */
    public function success(Request $request, SessionInterface $session): Response
    {
        $organization_token = $request->query->get('token');
        $organization_id = $request->query->get('id');

        if(!$organization_token || !$organization_id) {
            return $this->redirect('/', 301);
        }

        return $this->render('register_organization/success.html.twig', [
            'organization_id' => $organization_id
        ]);
    }
}
