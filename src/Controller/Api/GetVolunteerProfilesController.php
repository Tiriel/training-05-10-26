<?php

namespace App\Controller\Api;

use App\Repository\VolunteerProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class GetVolunteerProfilesController extends AbstractController
{
    #[Route('/api/profiles', name: 'app_api_get_volunteer_profiles')]
    public function __invoke(VolunteerProfileRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), context: [
            AbstractNormalizer::GROUPS => ['profile.list']
        ]);
    }
}
