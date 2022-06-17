<?php

declare(strict_types=1);

class Tournament
{
    private const TEAM_NAME = 'Team';
    private const TEAM_POINTS = 'P';
    private const TEAM_MATCHES_PLAYED = 'MP';
    private const TEAM_WINS = 'W';
    private const TEAM_DRAWS = 'D';
    private const TEAM_LOSSES = 'L';

    private const RESULT_WIN = 'win';
    private const RESULT_DRAW = 'draw';
    private const RESULT_LOSS = 'loss';

    private const POINTS_FOR_VICTORY = 3;
    private const POINTS_FOR_DRAW = 1;

    private const GAME_SEPARATOR = ';';
    private const ROW_FORMAT = '%-30s | %2s | %2s | %2s | %2s | %2s';

    private $teams = [];
    private $games = [];

    public function tally(string $score): string
    {
        $this->parse(
            $this->getGamesTokens($score)
        );
        $this->computeTournamentResults();
        $this->sortByPointsAndName();

        return $this->format();
    }

    private function getGamesTokens(string $score): Generator
    {
        if (empty($score)) {
            return;
        }

        foreach (explode(PHP_EOL, $score) as $gameLine) {
            yield explode(self::GAME_SEPARATOR, $gameLine);
        }
    }

    private function parse(Generator $gamesTokens): void
    {
        foreach ($gamesTokens as $gameToken) {
            [$homeTeam, $visitorTeam] = $gameToken;
            $this->setTeam($homeTeam);
            $this->setTeam($visitorTeam);

            $this->games[] = $gameToken;
        }
    }

    private function setTeam(string $teamName): void
    {
        if (!isset($this->teams[$teamName])) {
            $this->teams[$teamName] = [
                self::TEAM_NAME => $teamName,
                self::TEAM_WINS => 0, 
                self::TEAM_DRAWS => 0, 
                self::TEAM_LOSSES => 0, 
                self::TEAM_MATCHES_PLAYED => 0, 
                self::TEAM_POINTS => 0,
            ];
        }
    }

    private function computeTournamentResults(): void 
    {
        foreach ($this->games as $game) {
            [$homeTeam, $visitorTeam, $result] = $game;

            $this->teams[$homeTeam][self::TEAM_MATCHES_PLAYED]++;
            $this->teams[$visitorTeam][self::TEAM_MATCHES_PLAYED]++;

            switch ($result) {
                case self::RESULT_WIN:
                    $this->teams[$homeTeam][self::TEAM_WINS]++;
                    $this->teams[$homeTeam][self::TEAM_POINTS] += self::POINTS_FOR_VICTORY;
                    $this->teams[$visitorTeam][self::TEAM_LOSSES]++;
                    break;

                case self::RESULT_DRAW:
                    $this->teams[$homeTeam][self::TEAM_DRAWS]++;
                    $this->teams[$homeTeam][self::TEAM_POINTS] += self::POINTS_FOR_DRAW;
                    $this->teams[$visitorTeam][self::TEAM_DRAWS]++;
                    $this->teams[$visitorTeam][self::TEAM_POINTS] += self::POINTS_FOR_DRAW;
                    break;

                case self::RESULT_LOSS:
                    $this->teams[$homeTeam][self::TEAM_LOSSES]++;
                    $this->teams[$visitorTeam][self::TEAM_WINS]++;
                    $this->teams[$visitorTeam][self::TEAM_POINTS] += self::POINTS_FOR_VICTORY;
                    break;
            }            
        }
    }

    private function sortByPointsAndName(): void 
    {
        usort($this->teams, function(array $firstTeam, array $secondTeam) {
            return [$secondTeam[self::TEAM_POINTS], $firstTeam[self::TEAM_NAME]] <=> [$firstTeam[self::TEAM_POINTS], $secondTeam[self::TEAM_NAME]];
        });
    }

    private function format(): string
    {
        $output = [sprintf(
            self::ROW_FORMAT,
            self::TEAM_NAME,
            self::TEAM_MATCHES_PLAYED, 
            self::TEAM_WINS,
            self::TEAM_DRAWS,
            self::TEAM_LOSSES, 
            self::TEAM_POINTS,
        )];

        foreach ($this->teams as $team) {
            $output[] = sprintf(
                self::ROW_FORMAT,
                $team[self::TEAM_NAME],
                $team[self::TEAM_MATCHES_PLAYED],
                $team[self::TEAM_WINS],
                $team[self::TEAM_DRAWS],
                $team[self::TEAM_LOSSES],
                $team[self::TEAM_POINTS]
            );
        }

        return implode(PHP_EOL, $output);
    }
}
