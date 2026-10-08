<?php

namespace App\Ranking\Strategies;

use App\Entity\Conference;
use App\Entity\User;

final class SkillRanking implements RankingStrategyInterface
{
    public function __construct(
        private readonly int $weight,
    ) {}

    public function score(User $user, Conference $conference): int
    {
        $skills = $user->getVolunteerProfile()->getSkills();
        $shared = $conference->getNeededSkills()->filter(fn ($skill) => $skills->contains($skill));

        return $shared->count() * $this->weight;
    }
}
