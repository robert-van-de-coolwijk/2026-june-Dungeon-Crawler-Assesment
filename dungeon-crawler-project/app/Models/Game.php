<?php

namespace App\Models;

use App\Controllers\Commands\Command;
use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\Sentiment;
use App\Core\Tools;
use App\Models\GameState\AbstractGameState;
use App\Models\GameState\Blank;
use App\Models\WorldEntities\Player;
use App\Models\WorldEntities\Room;
use App\Models\WorldEntities\World;

/**
 * This is the central model, that contains ALL of the game
 *
 * // Current implementation relies on the following assertions:
 * // - There is only: 1 game, 1 game state, 1 player and 1 world
 * // - Every action taken is: singular, progressive, cumulative (where appropriate), chronological, definitive and final.
 *
 * The game state dictates the state of the game
 */
class Game extends SingletonPattern
{
    // @todo RC make a token based player identification to separate player interactions

    /**
     * @var ?Player the name playerOne is chosen as the "messiah", there is only one (in the current implementation)
     */
    protected ?Player $playerOne = null;

    protected World $world;


    protected function __construct()
    {
        parent::__construct();

        $this->world = new World();

        $this->populateFromCache();
    }


    public function handleCommand(string $commandName, array $params) : array
    {
        // determine game state
        $currentGameState = $this->getCurrentGameState();


//        if(!$currentGameState->validCommand($commandName, $params)){
//
//            return [
//                sprintf('Command "%s" is not available', $commandName),
//                sprintf('Use command', $commandName)
//            ];
//        }

        return Command::getInstance()->{$commandName}($params);

    }

    protected function getCurrentGameState(): AbstractGameState
    {

        return Blank::getInstance();
    }

    /// SUPPORT FUNCTIONS \\\

    public function getPlayerOne(): ?Player
    {
        return $this->playerOne;
    }

    public function setPlayerOne(Player $playerOne) : array
    {
        $msgs = array();

        $this->playerOne = $playerOne;

        $this->world->addEntity($playerOne);

        if($this->playerOne->isInsideContainer())
        {
            $msgs = array_merge($msgs, $this->placePlayerInRandomRoom($this->playerOne));
        }

        return $msgs;
    }

    public function getWorld(): ?World
    {
        return $this->world;
    }

    private function setWorld(World $world) : void
    {
        $msgs = array();

        $this->world = $world;
    }

    public function placePlayerInRandomRoom(Player $player) : array
    {
        $msg = array();

        Tools::debug($player);

        if(!is_null($player))
        {

            $randomRoom = $this->world->get(Room::class, CollectionPosition::First);


            Tools::debug($randomRoom);

            if(!is_null($randomRoom))
            {
                Tools::debug($randomRoom->id);

                $player->insideContainer = $randomRoom->id;


                Tools::debug($player->insideContainer);

                $msg[] = Tools::MsgWrap(sprintf('Player placed into room %s "%s"  ', $randomRoom->id, $randomRoom->name));
            }
        }

        return $msg;
    }

    /**
     * @return array Message Wrapper objects
     */
    public function getStateOfTheGame() : array
    {

        $playerString = $this->playerOne !== null   ? $this->playerOne->getStateOfThePlayer()   : '[ there is no player ]';
        $worldString = $this->world !== null        ? $this->world->getStateOfTheWorld()        : '[ there is no world ]';

        $MW = Tools::getMsgWrapFn();
        $emptyLine = '';

        return [
            $MW('Current state of the game',    ContType::H1),
            $MW('Player',                       ContType::H2),
            $MW($playerString,                  ContType::P),
            $MW($emptyLine,                     ContType::P),
            $MW('World:',                       ContType::H2),
            $MW($worldString,                   ContType::P),
        ];
    }

    public static function getInstance() : Game
    {
        return parent::getInstance();
    }

    public function __toString() : string
    {
        $stateOfTheGameArray = $this->getStateOfTheGame();

        return implode(PHP_EOL, $stateOfTheGameArray);
    }

    private function populateFromCache()
    {
        // see if there is a world on shared memory (Redis)

        // see if there is a world saved on disk

            // load player if exists
            // load world if exists
    }

    public function save() {
        $success = $this->world->save();

        if($success && !is_null($this->playerOne)){
            $success = $success && $this->playerOne->save();
        }

        return $success;
    }

    public function restore()
    {
        $success = $this->world->restore();

        if($success){
            $player = $this->world->get(Player::class, CollectionPosition::First);

            if($player !== null)
            {
                $this->playerOne = $player;

            }
        }

        return $success;
    }

}