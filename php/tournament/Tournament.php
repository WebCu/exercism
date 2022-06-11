<?php

declare(strict_types=1);

require_once 'TournamentLexer.php';
require_once 'TournamentParser.php';
require_once 'TournamentFormatter.php';

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

        $tournamentData = $this->parser->parse(
            $this->lexer->getGamesTokens()
        );
        $tournamentData->sortTeamsByPointsAndName();

        return $this->formatter->format($tournamentData->getTeams());
    }
}
