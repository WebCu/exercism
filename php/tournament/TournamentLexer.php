<?php

declare(strict_types=1);

require_once 'GameTokens.php';

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

