<?php

namespace App\Controllers;


use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\Sentiment;
use App\Core\Tools;
use App\Models\Game;

class Main
{
    // @todo RC make a token based player identification to separate player interactions


    // Current implementation relies on the following assertions:
    // There is only 1 player and 1 (game) state
    // Every action taken is: singular, progressive, cumulative (where appropriate), chronological, definitive and final.

    protected Game $game;

    public function start() : void {
        $this->game = Game::getInstance();
    }

    /**
     * @todo RC replace this with an interface
     */
    public function command(string $commandName, array $params) : array {

        return array_merge(
            [
                Tools::MsgWrap(
                    sprintf('%s %s', $commandName, implode(' ', $params)),
                    ContType::P,
                    Sentiment::Command
                )
            ],
            $this->game->handleCommand($commandName, $params)
        );

    }

    public function getGameState() : array
    {
        return $this->game->getStateOfTheGame();
    }
}