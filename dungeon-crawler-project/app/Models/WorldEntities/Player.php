<?php

namespace App\Models\WorldEntities;

use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\MsgWrap;
use App\Core\Tools;

class Player extends Creature
{
    public CreatureType $_creatureType = CreatureType::Player;

    public function getStateOfThePlayer() : MsgWrap
    {
        $msg = implode(PHP_EOL, [
            $this->_name,
            $this->_health
        ]);

        return Tools::MsgWrap($msg, ContType::P);
    }
}