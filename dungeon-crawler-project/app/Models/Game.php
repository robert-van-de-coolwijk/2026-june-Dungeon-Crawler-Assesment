<?php

namespace App\Models;

use App\Controllers\Commands\Command;
use App\Models\GameState\AbstractGameState;
use App\Models\GameState\GameState;
use App\Models\GameState\Blank;
use App\Models\WorldEntities\Player;
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
class Game
{
    // @todo RC make a token based player identification to separate player interactions

    /**
     * @var ?Player the name playerOne is chosen as the "messiah", there is only one (in the current implementation)
     */
    protected ?Player $playerOne = null;

    protected ?World $world = null;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        // see what is saved on disk

        // load game if exists
        // load player if exists
        // load world if exists

        // return game
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

}