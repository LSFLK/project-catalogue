<?php

namespace App\Controller;

use App\Entity\Organization;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class OrganizationsController extends AbstractController
{
    /**
     * @Route("/organizations", name="organizations")
     */
    public function index(): Response
    {
        $organizations = $this->getDoctrine()->getRepository(Organization::class)->findAllOrderByName();

        return $this->render('organizations/index.html.twig', [
            'organizations' => $organizations,
        ]);
    }

    /**
     * @Route("/organizations/search")
     */
    public function search(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        $organizations = $this->getDoctrine()->getRepository(Organization::class)->searchByOrganizationName($name);
        $content = $this->_getContentForRetrievedOrganizations($organizations);
        return new JsonResponse($content);
    }

    /**
     * @Route("/organizations/validate")
     */
    public function validate(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        $organization = $this->getDoctrine()->getRepository(Organization::class)->findOneBy(['name' => $name]);
        
        if($organization) { return new JsonResponse(false); }
        else { return new JsonResponse(true); }
    }

    private function _getContentForRetrievedOrganizations($organizations): array
    {
        $content = [];

        foreach($organizations as $organization) {
            $card = $this->renderView('organizations/card.html.twig', ['organization' => $organization]);
            array_push($content, $card);
        }

        return $content;
    }
}
