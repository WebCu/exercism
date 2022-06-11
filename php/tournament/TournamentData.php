<?php

declare(strict_types=1);

class TournamentData
{
    public function __construct(private TeamCollection $teams, private GameCollection $games)
    {
    }

    public function getTeams(): TeamCollection
    {
        return $this->teams;
    }

    public function getGames(): GameCollection
    {
        return $this->games;
    }

    public function sortTeamsByPointsAndName()
    {
        $this->teams->sortByPointsAndName();
    }
}

