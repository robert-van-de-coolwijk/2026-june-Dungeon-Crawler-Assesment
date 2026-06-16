<?php

namespace App\Models\GameState;

use App\Models\SingletonPattern;
use ArrayObject;

/**
 * GameState describes the current state of the game
 * as actions are taken the game goes from one state to another
 *
 * The idea is that "the whole game supports and allows everything",
 * the game state locks the player into certain limits so that there is a game state
 *
 * Possible game states:
 *
 * -- Game enter states --
 *
 * - Blank -
 * Condition: When there is no player, there is nothing.
 * Options: A player can be created.
 *
 * - Genesis -
 * Condition: One player exists, and is not inside a room
 * Options: A world can be created, the player will be placed in a room upon creation. The world decides default start location, if note is given a empty room is chosen at default, if none available, any room will be chosen.
 *
 * -- Game playing state --
 *
 * - Exploration -
 * Condition: The player is in a room,  health > 0
 * Options: move (north, east, south, west), look
 *
 * - Fight -
 * Condition: The player is in a room with creatures, health > 0
 * Options: attack, flee (go back to last room)
 *
 * - GameOver -
 * Condition: The players health is zero
 * Options: respawn (move player last room + reset health 100%), abandon (delete player), oblivion (delete world + player)
 *
 *
 */
abstract class AbstractGameState extends SingletonPattern
{

    /**
     * @TODO's for implementation
     * - get available commands / actions available in (each) game state
     * -- Determine generic commands
     * --- Admin: describes super user rights to do "terrible things"
     * ---- Destroy: removes + deletes ANY entity
     * -- implement commands / actions per game state
     */

    protected array $commands = [];

    /**
     * @var string Single term (fewest words possible) that describes the current game state
     */
    protected string $name;

    /**
     * @var string Describes what the game state is and what is expected from the player
     */
    protected string $description;

    protected function __construct()
    {
        parent::__construct();

        // admin commands
        $this->registerCommand('init');
        $this->registerCommand('trouble');

        // read only
        $this->registerCommand('state');
        $this->registerCommand('commands');
        $this->registerCommand('time');

        // save / restore
        $this->registerCommand('save');
        $this->registerCommand('restore');
    }

    protected function registerCommand(string $commandName, array $aliases = []) : void
    {
        if(isset($this->commands[$commandName])) {
            throw new \Exception(sprintf('Command "%s" is already registered on %s', $commandName, get_called_class()));
        }

        $this->commands[$commandName] = $commandName;

        //@todo RC this is not a great solution, improve all register command logic
        foreach ($aliases as $alias) {
            $this->registerCommand($alias);
        }

    }

    public function validCommand(string $commandName, array $params) : bool
    {
        return isset($this->commands[$commandName]);
    }

    public function commandList() : array
    {
        $commandArrayObject = new ArrayObject($this->commands);

        return $commandArrayObject->getArrayCopy();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string {
        return $this->description;
    }

}

/**
 * Idea's for future implementation
 *
 * Genesis describes the current actor controlling the game, is "a god". This works as it should be possible, to have a home world as a player.
 * Abandon a world, so no home is set. Then create a new world.
 * Abandon that second world and load any of the available worlds.
 *
 */