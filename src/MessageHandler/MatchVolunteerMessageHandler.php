<?php

namespace App\MessageHandler;

use App\Matching\Strategy\MatchingStrategyInterface;
use App\Matching\Strategy\TagBasedStrategy;
use App\Message\MatchVolunteerMessage;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use function Symfony\Component\DependencyInjection\Loader\Configurator\iterator;

#[AsMessageHandler]
final class MatchVolunteerMessageHandler{
    private iterable $strategies;

    public function __construct(
        private readonly UserRepository $userRepository,
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
        dump(
            sprintf("Matched user with id %d", $user->getId()),
            "Matches :",
            $matches
        );
    }
}
