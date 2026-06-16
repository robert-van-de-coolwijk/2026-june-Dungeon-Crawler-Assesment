<?php

namespace App\Models\GameState;


class Blank extends AbstractGameState
{

    /**
     * @TODO's for implementation
     *
     * - get available commands / actions:
     * -- name -> fills in the player name. [aliasses: born]
     *
     * - implement commands / actions
     */

    protected function __construct()
    {
        parent::__construct();

        $this->name = "Blank";
        $this->description = "Name your player, type \"player [name]\"";

        //$this->registerCommand('init');
        $this->registerCommand('player');
    }
}