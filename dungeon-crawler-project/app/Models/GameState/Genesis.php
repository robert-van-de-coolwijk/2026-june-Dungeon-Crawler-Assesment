<?php

namespace App\Models\GameState;


class Genesis extends AbstractGameState
{

    protected function __construct()
    {
        parent::__construct();

        $this->name = "Genesis";
        $this->description = "You are not standing in the world, use init or create commands to make rooms in the world and put the player in one.";

//        $this->registerCommand('init');
        $this->registerCommand('player');
    }
}