<?php

namespace App\Search;

use App\Repository\ConferenceRepository;

class DatabaseConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        private readonly ConferenceRepository $repository,
    ) {}

    public function search(?string $name): array
    {
        if (null === $name) {
            return $this->repository->findAll();
        }

        return $this->repository->findLikeName($name);
    }
}
