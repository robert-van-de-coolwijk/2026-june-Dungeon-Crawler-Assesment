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

   /// world administrative \\\
    public function init(array $params) : array
    {
        return Init::world($params);
    }

    public function save(array $params) : array
    {
        $game = Game::getInstance();

        $restoreSuccess = $game->getWorld()->save();

        return [
            Tools::MsgWrap($restoreSuccess ? "world saved to disk" : "Failed saving world to disk", ContType::P),
        ];
    }

    public function restore(array $params) : array
    {
        $game = Game::getInstance();

        $restoreSuccess = $game->restore();

        return [
            Tools::MsgWrap($restoreSuccess ? "world restored" : "Restore failed", ContType::P),
        ];
    }

    public function deleted(array $params) : array
    {
        $game = Game::getInstance();

        $restoreSuccess = $game->getWorld()->deleteSave();

        return [
            Tools::MsgWrap($restoreSuccess ? "world deleted" : "Failed deleting world", ContType::P),
        ];
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

    /// game actions \\\

    public function look(array $params) : array
    {
        $msg = [];
        $game = Game::getInstance();

        $currentRoomId = $game->getPlayerOne()->insideContainer;

        $currentRoom = $game->getWorld()->getEntityById($currentRoomId);

        if(!is_null($currentRoom)){
            $msg[] = Tools::MsgWrap(
                $currentRoom->name,
                ContType::P,
                Sentiment::Important
            );
            $msg[] = Tools::MsgWrap(
                $currentRoom->description,
                ContType::P,
                Sentiment::Normal
            );

            $msg[] = Tools::MsgWrap(
                '',
                ContType::P,
                Sentiment::Normal
            );

            $msg[] = Tools::MsgWrap(
                sprintf("[%s]", implode(', ', $currentRoom->getPortalNames())),
                ContType::P,
                Sentiment::Normal
            );
        }else {
            $msg[] = Tools::MsgWrap(
                '[ Player is not in a room ]',
                ContType::P
            );
        }


        return $msg;
    }

    //@todo RC move to be aliasses of an existing command (east is alias of "move east")
    public function north() : array {
        return $this->move(['north']);
    }

    public function east() : array {
        return $this->move(['east']);
    }

    public function south() : array {
        return $this->move(['south']);
    }

    public function west() : array {
        return $this->move(['west']);
    }

    public function move(array $params) : array
    {
        $msg = [];
        $game = Game::getInstance();
        $world = $game->getWorld();
        $playerOne = $game->getPlayerOne();

        $requestedPortalName = trim($params[0]);

        if(strlen($requestedPortalName) < 1){
            $msg[] = Tools::MsgWrap(
                sprintf('Invalid value for move action "%s"', $requestedPortalName),
                ContType::P
            );

            return $msg;
        }

        $currentRoomId = $game->getPlayerOne()->insideContainer;

        $currentRoom = $game->getWorld()->getEntityById($currentRoomId);

        if(is_null($currentRoom)){
            $msg[] = Tools::MsgWrap(
                'Player is not in a room!',
                ContType::P
            );

            return $msg;
        }


        $portalsAssoc = $currentRoom->getPortalNames();

        $portalId = array_search($requestedPortalName, $portalsAssoc);


        if(!is_string($portalId)){
            $msg[] = Tools::MsgWrap(
                'Can not move in that direction. No exit or portal with given name.',
                ContType::P
            );

            return $msg;
        }

        $portalEntity = $world->getEntityById($portalId);

        $playerOne->insideContainer = $portalEntity->target;

        $msg[] = Tools::MsgWrap(
            sprintf('You moved "%s":', $requestedPortalName),
            ContType::P
        );

        //@todo RC make this a player settable options or scriptable

        // immediately look into room
        $msg = array_merge(
            $msg,
            $this->look([])
        );


        return $msg;

    }

    /// read only helper functions \\\

    public function time() : array
    {
        return [
            Tools::getTimeStamp()
        ];
    }

}