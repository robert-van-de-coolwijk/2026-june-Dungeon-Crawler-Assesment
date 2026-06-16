<?php

namespace App\Models\GameState;


class GameWon extends AbstractGameState
{

    protected function __construct()
    {
        parent::__construct();

        $this->name = "Victory";
        $this->description = "You made it, you won!";
    }
}