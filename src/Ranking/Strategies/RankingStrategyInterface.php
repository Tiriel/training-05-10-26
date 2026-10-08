<?php

namespace App\Ranking\Strategies;

use App\Entity\Conference;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(tags: ['app.ranking_strategy'])]
interface RankingStrategyInterface
{
    public function score(User $user, Conference $conference): int;
}
