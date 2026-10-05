<?php

namespace App\MessageHandler;

use App\Matching\Strategy\MatchingStrategyInterface;
use App\Matching\Strategy\TagBasedStrategy;
use App\Message\MatchVolunteerMessage;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class MatchVolunteerMessageHandler{
    public function __construct(
        private readonly UserRepository $userRepository,
        #[Autowire(service: TagBasedStrategy::class)]
        private readonly MatchingStrategyInterface $strategy
    ) {}

    public function __invoke(MatchVolunteerMessage $message): void
    {
        $user = $this->userRepository->find($message->userId);
        if (null === $user) {
            return;
        }

        $matches = $this->strategy->match($user);
        dump(
            sprintf("Matched user with id %d", $user->getId()),
            "Matches :",
            $matches
        );
    }
}
