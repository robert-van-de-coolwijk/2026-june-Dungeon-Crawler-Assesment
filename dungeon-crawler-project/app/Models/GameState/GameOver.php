<?php

namespace App\Models\GameState;


class GameOver extends AbstractGameState
{

    protected function __construct()
    {
        parent::__construct();

        $this->name = "Game Over";
        $this->description = "Unfortunately, along your heroic path, you have found your end.";
    }
}