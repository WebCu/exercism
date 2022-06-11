<?php 

declare(strict_types=1);

require_once 'Team.php';

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
