<?php

/*
 * By adding type hints and enabling strict type checking, code can become
 * easier to read, self-documenting and reduce the number of potential bugs.
 * By default, type declarations are non-strict, which means they will attempt
 * to change the original type to match the type specified by the
 * type-declaration.
 *
 * In other words, if you pass a string to a function requiring a float,
 * it will attempt to convert the string value to a float.
 *
 * To enable strict mode, a single declare directive must be placed at the top
 * of the file.
 * This means that the strictness of typing is configured on a per-file basis.
 * This directive not only affects the type declarations of parameters, but also
 * a function's return type.
 *
 * For more info review the Concept on strict type checking in the PHP track
 * <link>.
 *
 * To disable strict typing, comment out the directive below.
 */

declare(strict_types=1);

class Tournament
{
    private $teams = [];

    public function tally($score): string
    {
        $this->registerResults(explode(PHP_EOL, $score));
        $this->sortTeams();

        return $this->printTable();
    }

    private function registerResults($matches): void
    {
        foreach ($matches as $match) {
            if ('' === $match) {
                continue;
            }

            list($homeTeam, $visitorTeam, $result) = explode(';', $match);
            
            $this->registerResult(
                $this->getTeam($homeTeam),
                $this->getTeam($visitorTeam),
                $result
            );
        }
    }

    private function getTeam(string $teamName): Team
    {
        if (!isset($this->teams[$teamName])) {
            $this->teams[$teamName] = new Team($teamName);
        }

        return $this->teams[$teamName];
    }

    private function registerResult(Team $homeTeam, Team $visitorTeam, string $result): void
    {
        switch ($result) {
            case 'win':
                $homeTeam->addWin();
                $visitorTeam->addLoss();
                break;

            case 'draw':
                $homeTeam->addDraw();
                $visitorTeam->addDraw();
                break;

            case 'loss':
                $homeTeam->addLoss();
                $visitorTeam->addWin();
                break;
        }            
    }

    private function sortTeams(): void 
    {
        usort($this->teams, function(Team $firstTeam, Team $secondTeam) {
            return [-1 * $firstTeam->points(), $firstTeam->getName()] <=> [-1 * $secondTeam->points(), $secondTeam->getName()];
        });
    }

    private function printTable(): string
    {
        $table = $this->getTableHeader();
        $tableBody = $this->getTableBody();

        if ('' !== $tableBody) {
            $table .= PHP_EOL . $tableBody;
        } 

        return $table;
    }

    private function getTableHeader()
    {
        $tableHeader = [sprintf("%-30s", 'Team')];
        $tableHeader[] = sprintf("%3s", 'MP');
        $tableHeader[] = sprintf("%3s", 'W');
        $tableHeader[] = sprintf("%3s", 'D');
        $tableHeader[] = sprintf("%3s", 'L');
        $tableHeader[] = sprintf("%3s", 'P');

        return join(' |', $tableHeader);
    }

    private function getTableBody()
    {
        $tableBody = [];

        foreach ($this->teams as $team) {
            $formatedTeam = [sprintf("%-30s", $team->getName())];
            $formatedTeam[] = sprintf("%3s", $team->getMatchesPlayed());
            $formatedTeam[] = sprintf("%3s", $team->getWins());
            $formatedTeam[] = sprintf("%3s", $team->getDraws());
            $formatedTeam[] = sprintf("%3s", $team->getLosses());
            $formatedTeam[] = sprintf("%3s", $team->points());

            $tableBody[] = join(' |', $formatedTeam);
        }

        return join(PHP_EOL, $tableBody);
    }
}

class Team 
{
    public function __construct(
        private $name,
        private $matchesPlayed = 0,
        private $wins = 0,
        private $draws = 0,
        private $losses = 0
    )
    {
    }

    public function addWin(): void 
    {
        $this->wins++;
        $this->matchesPlayed++;
    }

    public function addDraw(): void 
    {
        $this->draws++;
        $this->matchesPlayed++;
    }

    public function addLoss(): void 
    {
        $this->losses++;
        $this->matchesPlayed++;
    }

    public function points(): int 
    {
        return $this->wins * 3 + $this->draws;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMatchesPlayed(): int
    {
        return $this->matchesPlayed;
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
