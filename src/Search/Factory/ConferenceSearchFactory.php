<?php

namespace App\Search\Factory;

use App\Search\Client\ConferenceApiClient;
use App\Search\ConferenceSearchInterface;
use App\Search\DatabaseConferenceSearch;
use Symfony\Component\DependencyInjection\Attribute\Lazy;

class ConferenceSearchFactory
{
    public function __construct(
        #[Lazy]
        private readonly DatabaseConferenceSearch $databaseSearch,
        #[Lazy]
        private readonly ConferenceApiClient $client,
    ) {}

    public function create(string $search): ConferenceSearchInterface
    {
        return match ($search) {
            'database' => $this->databaseSearch,
            'api' => $this->client,
            default => throw new \InvalidArgumentException()
        };
    }
}
