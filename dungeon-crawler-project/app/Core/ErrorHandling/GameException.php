<?php

namespace App\Core\ErrorHandling;

/**
 * Specific Exception classes to differentiate between:
 *  user errors: Things an actor does, that are not valid.
 *  access error: Things an actor does, that are valid, but not allowed
 *  flow errors: things that should not be happening, which are soft programming errors; that do not constitute a game crash
 *
 * There exceptions should be caught and typically let you just continue play the game without interruptions,
 * but will be logged and might be flagged for a closer look.
 */
class GameException extends \Exception
{

}