<?php

namespace App\Models\GameState;


class Fight extends AbstractGameState
{
    /**
     * @TODO's for implementation
     *
     * - get available commands / actions:
     * -- [attack name]
     * -- look: @room, [creature name]
     *
     * - implement commands / actions
     */

    protected function __construct()
    {
        parent::__construct();

        $this->name = "Fight";
        $this->description = "You are in altercation with unsavory individuals. Resist, fight or flee for your life";

        $this->registerCommand('look');

        $this->registerCommand('fight');
        $this->registerCommand('flee');
    }
}