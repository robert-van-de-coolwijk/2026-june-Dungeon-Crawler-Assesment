<?php

namespace App\Controllers;


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
        return $this->game->handleCommand($commandName, $params);
    }

    public function getGameState() : array
    {
        return $this->game->getStateOfTheGame();
    }
}