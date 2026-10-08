<?php

namespace App\Ranking;

use App\Entity\Conference;
use App\Entity\User;
use App\Ranking\Strategies\RankingStrategyInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class RankingHandler
{
    public function __construct(
        #[AutowireIterator('app.ranking_strategy')]
        private readonly iterable $strategies,
    ) {}

    public function rank(User $user, iterable $conferences): array
    {
        $unique = [];
        foreach ($conferences as $conference) {
            $unique[$conference->getId()] = $conference;
        }

        $scores = [];
        foreach ($unique as $id => $conference) {
            $scores[$id] = 0;
            foreach ($this->strategies as $strategy) {
                $scores[$id] += $strategy->score($user, $conference);
            }
        }

        arsort($scores);

        return array_values(array_map(fn (int $id) => $unique[$id], array_keys($scores)));
    }
}
