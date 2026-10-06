<?php

namespace App\Controller\Api;

use App\Entity\VolunteerProfile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class GetVolunteerProfileController extends AbstractController
{
    #[Route('/api/profile/{id:profile}', name: 'app_api_get_volunteer_profile')]
    public function __invoke(VolunteerProfile $profile): JsonResponse
    {
        return $this->json($profile, context: [
            AbstractNormalizer::GROUPS => ['profile.get']
        ]);
    }
}
