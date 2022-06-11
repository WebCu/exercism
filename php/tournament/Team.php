<?php

declare(strict_types=1);

class Team 
{
    public function __construct(
        private string $name,
        private int $wins = 0,
        private int $draws = 0,
        private int $losses = 0
    )
    {
    }

    public function addWin(): void 
    {
        $this->wins++;
    }

    public function addDraw(): void 
    {
        $this->draws++;
    }

    public function addLoss(): void 
    {
        $this->losses++;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPoints(): int 
    {
        return $this->wins * 3 + $this->draws;
    }

    public function getMatchesPlayed(): int
    {
        return $this->wins + $this->draws + $this->losses;
    }

    public function getWins(): int
    {
        return $this->wins;
    }

    public function getDraws(): int
    {
        return $this->draws;
    }

    public function getLosses(): int
    {
        return $this->losses;
    }
}
