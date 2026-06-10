<?php

namespace App\Models\GameState;

use App\Models\WorldEntities\Player;
use App\Models\WorldEntities\World;

/**
 * GameState describes the current state of the game
 * as actions are taken the game goes from one state to another
 *
 * Possible game states:
 *
 * -- Game enter states --
 *
 * - Void -
 * Condition: When there is no player, there is nothing.
 * Options: A player can be created.
 *
 * - Genesis -
 * Condition: One player exists
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
class GameState
{

    /**
     * @var ?Player the name playerOne is chosen as the "messiah" there is only one (in the current implementation)
     * The player will be filled in
     */
    protected ?Player $playerOne = null; // the name playerOne is chosen as the "messiah" there is only one (in the current implementation)

    protected ?World $world = null;


    /**
     * @TODO's for implementation
     * - get available commands / actions available in (each) game state
     * -- Determine generic commands
     * --- Admin: describes super user rights to do "terrible things"
     * ---- Destroy: removes + deletes ANY entity
     * -- implement commands / actions per game state
     */







}

/**
 * Idea's for future implementation
 *
 * Genesis describes the current actor controlling the game, is "a god". This works as it should be possible, to have a home world as a player.
 * Abandon a world, so no home is set. Then create a new world.
 * Abandon that second world and load any of the available worlds.
 *
 */