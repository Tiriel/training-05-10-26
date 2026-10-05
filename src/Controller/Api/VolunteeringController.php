<?php

namespace App\Controller\Api;

use App\Repository\VolunteeringRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class VolunteeringController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/api/volunteering', name: 'app_api_get_volunteering', methods: ['GET'])]
    public function getVolunteering(Request $request, VolunteeringRepository $repository): Response
    {
        $limit = 20;
        $page = $request->query->get('page', 1);
        $volunteering = $repository->findBy([], [], $limit, ($page - 1) * $limit);

        return $this->json($volunteering, Response::HTTP_OK, context: ['groups' => ['Volunteering']]);
    }
}
