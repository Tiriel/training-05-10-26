<?php

namespace App\Search\Client;

use App\Search\ConferenceSearchInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsAlias]
class ConferenceApiClient implements ConferenceSearchInterface
{
    public function __construct(
        #[Target('conferences.client')]
        private readonly HttpClientInterface $client,
    ) {}

    public function search(?string $name): array
    {
        return $this->client->request(
            'GET',
            '/events',
            ['query' => ['name' => $name]]
        )->toArray();
    }
}
