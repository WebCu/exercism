<?php 

declare(strict_types=1);

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
