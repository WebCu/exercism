<?php

declare(strict_types=1);

class Tournament
{
    private TournamentLexer $lexer;
    private TournamentParser $parser;
    private TournamentFormatter $formatter;

    public function __construct()
    {
       $this->lexer = new TournamentLexer(); 
       $this->parser = new TournamentParser(); 
       $this->formatter = new TournamentFormatter(); 
    }

    public function tally($score): string
    {
        $this->lexer->setInput($score);

        $teams = $this->parser->parse(
            $this->lexer->getGamesTokens()
        );
        $teams->sortByPointsAndName();

        return $this->formatter->format($teams);
    }
}

class TournamentLexer
{
    private $text;
    private $gameSeparator = ';';

    public function setInput(string $input): void
    {
        $this->text = $input;
    }

    public function getGamesTokens(): Generator
    {
        if (empty($this->text)) {
            return;
        }

        foreach (explode(PHP_EOL, $this->text) as $gameLine) {
            yield $this->getGameTokens($gameLine);
        }
    }

    private function getGameTokens(string $gameLine): GameTokens
    {
        return new GameTokens(...explode($this->gameSeparator, $gameLine));
    }
}

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

class TournamentParser
{
    public function parse(Generator $gamesTokens): TeamCollection
    {
        $teams = new TeamCollection();

        foreach ($gamesTokens as $gameToken) {
            $homeTeam = $teams->getTeam($gameToken->homeTeam);
            $visitorTeam = $teams->getTeam($gameToken->visitorTeam);

            new Game($homeTeam, $visitorTeam, $gameToken->result);
        }

        return $teams;
    }
}

class TeamCollection
{
    private array $teams = [];

    public function getTeams(): array
    {
        return $this->teams;
    }

    public function getTeam(string $teamName): Team
    {
        return $this->teams[$teamName] ??= new Team($teamName);
    } 

    public function sortByPointsAndName(): void 
    {
        usort($this->teams, function(Team $firstTeam, Team $secondTeam) {
            return [$secondTeam->getPoints(), $firstTeam->getName()] <=> [$firstTeam->getPoints(), $secondTeam->getName()];
        });
    }
}

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

class Game
{
    public function __construct(
        public Team $homeTeam,
        public Team $visitorTeam,
        public string $result
    )
    {
        switch ($result) {
            case Result::WIN->value:
                $homeTeam->addWin();
                $visitorTeam->addLoss();
                break;

            case Result::DRAW->value:
                $homeTeam->addDraw();
                $visitorTeam->addDraw();
                break;

            case Result::LOSS->value:
                $homeTeam->addLoss();
                $visitorTeam->addWin();
                break;
        }            
    }

}

enum Result: string
{
    case WIN = 'win';
    case DRAW = 'draw';
    case LOSS = 'loss';
}

class TournamentFormatter
{
    private const COLS_FORMAT = [
        'Team' => '%-30s',
        'MP' => '%2s',
        'W' => '%2s',
        'D' => '%2s',
        'L' => '%2s',
        'P' => '%2s'
    ];
    private const SEPARATOR = ' | ';

    private $format; 

    public function __construct()
    {
        $this->format = implode(self::SEPARATOR, self::COLS_FORMAT);
    }

    public function format(TeamCollection $teams): string
    {
        $output = [$this->getTableHeader()]; 

        foreach ($teams->getTeams() as $team) {
            $output[] = $this->transformTeamToRow($team);
        }

        return implode(PHP_EOL, $output);
    }

    private function getTableHeader(): string
    {
        return $this->toRow('Team', 'MP', 'W', 'D', 'L', 'P');
    }

    private function transformTeamToRow(Team $team)
    {
        return $this->toRow(
            $team->getName(),
            $team->getMatchesPlayed(),
            $team->getWins(),
            $team->getDraws(),
            $team->getLosses(),
            $team->getPoints(),
        );
    }

    private function toRow($name, $matchesPlayed, $wins, $draws, $losses, $points): string
    {
        return sprintf($this->format, $name, $matchesPlayed, $wins, $draws, $losses, $points);
    }
}
