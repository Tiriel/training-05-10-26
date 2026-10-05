<?php

namespace App\Transformer;

use App\Entity\Conference;

class ApiToConferenceTransformer implements ApiToEntityTransformerInterface
{

    public function transform(array $data): object
    {
        return (new Conference())
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setPrerequisites($data['prerequisites'])
            ->setAccessible($data['accessible'])
            ->setStartAt(new \DateTimeImmutable($data['startDate']))
            ->setEndAt(new \DateTimeImmutable($data['endDate']))
        ;
    }
}
