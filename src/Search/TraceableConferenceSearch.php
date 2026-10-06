<?php

namespace App\Search;

use App\Search\ConferenceSearchInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;

#[AsDecorator(ConferenceSearchInterface::class, priority: 10)]
class TraceableConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        private readonly ConferenceSearchInterface $inner,
        private readonly LoggerInterface $logger,
    ) {}

    public function search(?string $name): array
    {
        $this->logger->info('Performing conference search.', ['name' => $name]);

        return $this->inner->search($name);
    }
}
