<?php

namespace App\Controllers\Commands;

use App\Core\Tools;
use App\Models\Game;
use App\Models\GameState\Blank;
use App\Models\SingletonPattern;

/**
 * This is a command a player (or actor) can execute on the game world.
 * Commands are agnostic and do not differentiate who did what and why.
 *
 * WHEN IMPLEMENTED
 * If a command is allowed in certain contexts and with certain values should be controlled by the GameEntity's themselves using the Lock property of an entity.
 * CURRENT implementation always accept a command IF the command exists and the entity and when applicable said property exists.
 *
 * Entities are responsible to reflect through Exceptions if things are allowed because you are allow to and if they exist.
 * See GameException for specifics
 *
 */
class Command extends SingletonPattern
{

    public function init(Game $game, array $params) : array {

        return [
            "Not implemented, init needs content"
        ];
    }

    public function player(Game $game, array $params) : array
    {


        return [
            "Not implemented, player not named"
        ];
    }

    public function move(Game $game, array $params) : array
    {


        return [
            "Not implemented, player is not moved"
        ];
    }

    public function time() : array
    {
        return [
            Tools::getTimeStamp()
        ];
    }

}