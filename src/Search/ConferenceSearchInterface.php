<?php

namespace App\Search;

use App\Entity\Conference;

interface ConferenceSearchInterface
{
    public function search(?string $name): array;
}
