<?php

declare(strict_types=1);

require_once 'Result.php';

class Game
{
    public function __construct(
        public Team $homeTeam,
        public Team $visitorTeam,
        public string $result
    )
    {
        $resultEnum = Result::from($result);

        switch ($resultEnum) {
            case Result::WIN:
                $homeTeam->addWin();
                $visitorTeam->addLoss();
                break;

            case Result::DRAW:
                $homeTeam->addDraw();
                $visitorTeam->addDraw();
                break;

            case Result::LOSS:
                $homeTeam->addLoss();
                $visitorTeam->addWin();
                break;
        }            
    }

}

