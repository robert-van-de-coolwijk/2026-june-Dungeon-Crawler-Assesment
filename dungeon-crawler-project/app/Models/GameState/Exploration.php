<?php

namespace App\Models\GameState;


class Exploration extends AbstractGameState
{

    protected function __construct()
    {
        parent::__construct();

        $this->name = "Exploration";
        $this->description = "Move around and enjoy yourself";

        $this->registerCommand('move', ['north', 'east', 'south', 'west', 'walk', 'run']); // @todo make aliases work
        $this->registerCommand('look');
    }
}