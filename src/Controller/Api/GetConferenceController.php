<?php

namespace App\Controller\Api;

use App\Entity\Conference;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class GetConferenceController extends AbstractController
{
    #[Route('/api/conference/{id:conference}', name: 'app_api_get_conference')]
    public function __invoke(Conference $conference): JsonResponse
    {
        return $this->json($conference, context: [
            AbstractNormalizer::GROUPS => ['conference.get'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => static fn(object $obj) => [
                'id' => $obj->getId(),
            ],
        ]);
    }
}
