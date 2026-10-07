<?php

namespace App\MessageHandler;

use App\Matching\Strategy\MatchingStrategyInterface;
use App\Matching\Strategy\TagBasedStrategy;
use App\Message\MatchVolunteerMessage;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use function Symfony\Component\Clock\now;

#[AsMessageHandler]
final class MatchVolunteerMessageHandler{
    private iterable $strategies;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly CacheInterface $cache,
        #[AutowireIterator(tag: 'app.matching_strategy', defaultIndexMethod: 'getName')]
        iterable $strategies,
    ) {
        $this->strategies = $strategies instanceof \Traversable ? iterator_to_array($strategies) : $strategies;
    }

    public function __invoke(MatchVolunteerMessage $message): void
    {
        $user = $this->userRepository->find($message->userId);
        if (null === $user) {
            return;
        }

        $matches = [];
        foreach ($this->strategies as $strategy) {
            $matches = \array_merge($strategy->match($user), $matches);
        }
        $user->getVolunteerProfile()->setUpdatedAt(now());

        $key = sprintf(
            "%d-%s",
            $user->getId(),
            $user->getVolunteerProfile()->getUpdatedAt()->format('Y-m-d')
        );
        $this->cache->get($key, function (ItemInterface $item) use ($matches) {
            $item
                ->set($matches ?? [])
                ->expiresAfter(3600)
                ->tag('app.volunteer.matches')
                ;

            return $item->get();
        });

        dump(
            sprintf("Matched user with id %d", $user->getId()),
            "Matches :",
            $matches
        );
    }
}
