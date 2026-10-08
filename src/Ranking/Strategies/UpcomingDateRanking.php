<?php

namespace App\Ranking\Strategies;

use App\Entity\Conference;
use App\Entity\User;
use Psr\Clock\ClockInterface;

final class UpcomingDateRanking implements RankingStrategyInterface
{
    private const HORIZON_DAYS = 90;

    public function __construct(
        private readonly int $weight,
        private readonly ClockInterface $clock,
    ) {}

    public function score(User $user, Conference $conference): int
    {
        $days = $this->clock->now()->diff($conference->getStartAt());

        if ($days->invert || $days->days > self::HORIZON_DAYS) {
            return 0;
        }

        return intdiv(self::HORIZON_DAYS - $days->days, 30) * $this->weight;
    }
}
