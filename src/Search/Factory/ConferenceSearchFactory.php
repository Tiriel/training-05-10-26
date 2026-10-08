<?php

namespace App\Search\Factory;

use App\Search\Client\ConferenceApiClient;
use App\Search\ConferenceSearchInterface;
use App\Search\DatabaseConferenceSearch;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

class ConferenceSearchFactory
{
    public function __construct(
        #[AutowireLocator([
            'database' => DatabaseConferenceSearch::class,
            'api' => ConferenceApiClient::class
        ])]
        private readonly ContainerInterface $searches
    ) {}

    public function create(string $search): ConferenceSearchInterface
    {
        return $this->searches->get($search);
    }
}
