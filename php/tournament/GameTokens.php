<?php 

declare(strict_types=1);

class GameTokens
{
    public function __construct(
        public string $homeTeam,
        public string $visitorTeam,
        public string $result
    )
    {
    }
}

