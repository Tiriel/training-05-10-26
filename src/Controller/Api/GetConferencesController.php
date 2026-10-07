<?php

namespace App\Controller\Api;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class GetConferencesController extends AbstractController
{
    #[Cache(
        smaxage: 1000,
        public: true,
    )]
    #[Route('/api/conferences', name: 'app_api_get_conferences', methods: ['GET'])]
    public function __invoke(Request $request, ConferenceRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $list = $serializer->serialize($repository->findAll(), 'json', context: [
            AbstractNormalizer::GROUPS => ['conference.list'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => static fn(object $obj) => [
                'id' => $obj->getId(),
            ]
        ]);

        $response = (new JsonResponse())->setEtag(md5($list));
        if ($response->isNotModified($request)) {
            return $response->setPublic();
        }


        return $response
            ->setContent($list)
            ->setPublic()
        ;
    }
}
