<?php

namespace App\Controllers\Commands;

use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\Sentiment;
use App\Core\Tools;
use App\Models\Game;
use App\Models\GameState\Blank;
use App\Models\SingletonPattern;
use App\Models\WorldEntities\Player;

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

    /**
     * @throws \Exception
     */
    public function init(array $params) : array
    {
        return Init::world($params);
    }

    public function restore(array $params) : array
    {
        $game = Game::getInstance();

        $game->getWorld()->restore();

        return [Tools::MsgWrap("world restored")];
    }

    public function player(array $params) : array
    {
        $msg = [];
        $game = Game::getInstance();

        $playerOne = $game->getPlayerOne();

        if($playerOne === null && isset($params[0]))
        {
            $playerOne = new Player();
            $playerOne->name = $params[0];

            $world = $game->getWorld();

            $msg[] = Tools::MsgWrap('Created player', ContType::P, Sentiment::Important);

            $game->setPlayerOne($playerOne);
        }

        $msg[] = Tools::MsgWrap(
            $playerOne?->__toString() ?? 'No player set',
            ContType::P
        );

        return $msg;
    }

    public function state(array $params) : array
    {
        $game = Game::getInstance();

        return $game->getStateOfTheGame();
    }

    public function move(array $params) : array
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