<?php

namespace App\Search;

use App\Entity\Organization;
use App\Repository\ConferenceRepository;

class DatabaseConferenceSearch implements ConferenceSearchInterface
{
    private array $orgIds = [];
    private int $maxResults = 10;

    public function __construct(
        private readonly ConferenceRepository $repository,
    ) {}

    public function search(?string $name): array
    {
        return $this->repository->getForNameWithOrgAndMaxResults($this->orgIds, $this->maxResults, $name);
    }

    public function setOrgIds(array $orgIds): DatabaseConferenceSearch
    {
        $this->orgIds = $orgIds;

        return $this;
    }

    public function setMaxResults(int $maxResults): DatabaseConferenceSearch
    {
        $this->maxResults = $maxResults;

        return $this;
    }

}
