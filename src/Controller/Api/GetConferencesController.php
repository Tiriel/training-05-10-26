<?php

namespace App\Controller\Api;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class GetConferencesController extends AbstractController
{
    #[Route('/api/conferences', name: 'app_api_get_conferences', methods: ['GET'])]
    public function __invoke(ConferenceRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), context: [
            AbstractNormalizer::GROUPS => ['conference.list'],
        ]);
    }
}
