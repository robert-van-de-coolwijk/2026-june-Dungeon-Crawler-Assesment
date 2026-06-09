<?php

namespace App\Controllers\Commands;

/**
 * This is a command a player (or actor) can execute on the game world.
 * Commands are agnostic and do not differentiate who did what and why.
 *
 * WHEN IMPLEMENTED
 * If a command is allowed in certain contexts and with certain values should be controlled by the GameEntity's themselves using the Lock property of an entity.
 * CURRENT implementation always accept a command IF the command exists and the entity and when applicable said property exists.
 *
 * Entities are responsible to reflect through Exceptions if things are allowed because you are allow to and if they exist.
 * See GameException for specific
 *
 */
abstract class Command
{

}