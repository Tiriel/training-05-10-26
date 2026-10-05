<?php

namespace App\Search;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[AsDecorator(ConferenceSearchInterface::class)]
class CacheableConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        private readonly ConferenceSearchInterface $inner,
        private readonly CacheInterface $cache,
    ) {}

    public function search(?string $name): array
    {
        return $this->cache->get(md5($name), function (ItemInterface $item) use ($name) {
            $item->expiresAfter(3600);

            return $this->inner->search($name);
        });
    }
}
