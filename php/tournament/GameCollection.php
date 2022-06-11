<?php 

declare(strict_types=1);

require_once 'Game.php';

class GameCollection
{
    private array $games = [];

    public function addGame(Team $homeTeam, Team $visitorTeam, string $result): void
    {
        $this->games[] = new Game($homeTeam, $visitorTeam, $result);
    } 
}
