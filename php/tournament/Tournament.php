<?php

declare(strict_types=1);

class Tournament
{
    private const NAME = 'name';
    private const POINTS = 'points';
    private const MATCHES_PLAYED = 'mp';
    private const WIN = 'win';
    private const DRAW = 'draw';
    private const LOSS = 'loss';

    private const POINTS_FOR_VICTORY = 3;
    private const POINTS_FOR_DRAW = 1;

    private const GAME_SEPARATOR = ';';
    private const COLS_SEPARATOR = ' | ';
    private const COLS_FORMAT = [
        'Team' => '%-30s',
        'MP' => '%2s',
        'W' => '%2s',
        'D' => '%2s',
        'L' => '%2s',
        'P' => '%2s'
    ];

    private $format; 
    private $teams = [];

    public function __construct()
    {
        $this->format = implode(self::COLS_SEPARATOR, self::COLS_FORMAT);
    }

    public function tally(string $score): string
    {
        $this->parse(
            $this->getGamesTokens($score)
        );
        $this->sortByPointsAndName();

        return $this->format();
    }

    public function getGamesTokens(string $score): Generator
    {
        if (empty($score)) {
            return;
        }

        foreach (explode(PHP_EOL, $score) as $gameLine) {
            yield $this->getGameTokens($gameLine);
        }
    }

    private function getGameTokens(string $gameLine): array
    {
        return explode(self::GAME_SEPARATOR, $gameLine);
    }

    public function parse(Generator $gamesTokens): void
    {
        foreach ($gamesTokens as $gameToken) {
            [$homeTeam, $visitorTeam, $result] = $gameToken;
            $this->setTeam($homeTeam);
            $this->setTeam($visitorTeam);

            $this->increaseMatchesPlayed($homeTeam);
            $this->increaseMatchesPlayed($visitorTeam);

            switch ($result) {
                case self::WIN:
                    $this->increaseVictories($homeTeam);
                    $this->increaseLosses($visitorTeam);
                    break;

                case self::DRAW:
                    $this->increaseDraws($homeTeam);
                    $this->increaseDraws($visitorTeam);
                    break;

                case self::LOSS:
                    $this->increaseLosses($homeTeam);
                    $this->increaseVictories($visitorTeam);
                    break;
            }            
        }
    }

    public function setTeam(string $teamName): void
    {
        if (!isset($this->teams[$teamName])) {
            $this->teams[$teamName] = [self::NAME => $teamName, self::WIN => 0, self::DRAW => 0, self::LOSS => 0, self::MATCHES_PLAYED => 0, self::POINTS => 0];
        }
    }

    public function increaseMatchesPlayed(string $teamName): void
    {
        $this->teams[$teamName][self::MATCHES_PLAYED]++;
    }

    public function increaseVictories(string $teamName): void
    {
        $this->teams[$teamName][self::WIN]++;
        $this->teams[$teamName][self::POINTS] += self::POINTS_FOR_VICTORY;
    }

    public function increaseDraws(string $teamName): void
    {
        $this->teams[$teamName][self::DRAW]++;
        $this->teams[$teamName][self::POINTS] += self::POINTS_FOR_DRAW;
    }

    public function increaseLosses(string $teamName): void
    {
        $this->teams[$teamName][self::LOSS]++;
    }

    public function sortByPointsAndName(): void 
    {
        usort($this->teams, function(array $firstTeam, array $secondTeam) {
            return [$secondTeam[self::POINTS], $firstTeam[self::NAME]] <=> [$firstTeam[self::POINTS], $secondTeam[self::NAME]];
        });
    }

    public function format(): string
    {
        $output = [sprintf($this->format, 'Team', 'MP', 'W', 'D', 'L', 'P')];

        foreach ($this->teams as $team) {
            $output[] = $this->toRow($team);
        }

        return implode(PHP_EOL, $output);
    }

    private function toRow(array $team): string
    {
        return sprintf($this->format, $team[self::NAME], $team[self::MATCHES_PLAYED], $team[self::WIN], $team[self::DRAW], $team[self::LOSS], $team[self::POINTS]);
    }
}
