<?php

declare(strict_types=1);

require_once 'TeamCollection.php';
require_once 'GameCollection.php';
require_once 'TournamentData.php';

class TournamentParser
{
    public function parse(Generator $gamesTokens): TournamentData
    {
        $teams = new TeamCollection();
        $games = new GameCollection();

        foreach ($gamesTokens as $gameToken) {
            $homeTeam = $teams->getTeam($gameToken->homeTeam);
            $visitorTeam = $teams->getTeam($gameToken->visitorTeam);

            $games->addGame($homeTeam, $visitorTeam, $gameToken->result);
        }

        return new TournamentData($teams, $games);
    }
}
