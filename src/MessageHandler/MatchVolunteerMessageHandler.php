<?php

namespace App\MessageHandler;

use App\Matching\Strategy\MatchingStrategyInterface;
use App\Matching\Strategy\TagBasedStrategy;
use App\Message\MatchVolunteerMessage;
use App\Ranking\RankingHandler;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use function Symfony\Component\Clock\now;

#[AsMessageHandler]
final class MatchVolunteerMessageHandler{
    private iterable $strategies;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly RankingHandler $rankingHandler,
        #[Target('volunteer.matches.cache')]
        private readonly TagAwareCacheInterface $cache,
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

        $user->getVolunteerProfile()->setUpdatedAt(now());
        $this->entityManager->flush();

        $key = sprintf(
            "%d-%s",
            $user->getId(),
            $user->getVolunteerProfile()->getUpdatedAt()->format('Y-m-d')
        );
        $matches = $this->cache->get($key, function (ItemInterface $item) use ($user): array {
            $item->tag(['app.volunteer.matches']);

            $matches = [];
            foreach ($this->strategies as $strategy) {
                $matches = [...$matches, ...$strategy->match($user)];
            }

            return $this->rankingHandler->rank($user, $matches);
        });

        dump(
            sprintf("Matched user with id %d", $user->getId()),
            "Matches :",
            $matches
        );
    }
}
