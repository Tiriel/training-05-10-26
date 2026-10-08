<?php

namespace App\Ranking\Strategies;

use App\Entity\Conference;
use App\Entity\User;

final class TagRanking implements RankingStrategyInterface
{
    public function __construct(
        private readonly int $weight,
    ) {}

    public function score(User $user, Conference $conference): int
    {
        $interests = $user->getVolunteerProfile()->getInterests();
        $shared = $conference->getTags()->filter(fn ($tag) => $interests->contains($tag));

        return $shared->count() * $this->weight;
    }
}
