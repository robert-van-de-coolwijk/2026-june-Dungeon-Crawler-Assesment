<?php

namespace App\Models\WorldEntities;

use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\MsgWrap;
use App\Core\Tools;

class Player extends Creature
{
    public CreatureType $_creatureType = CreatureType::Player;

    public ?Room $_currentRoom = null;

    public function getStateOfThePlayer() : MsgWrap
    {
        $msg = implode(PHP_EOL, [
            $this->_name,
            sprintf('Health %s of %s', $this->_health, $this->_health->getMaxValue()),
            sprintf('Room %s', $this?->_currentRoom ?? '[ No room ]'),
        ]);

        return Tools::MsgWrap($msg, ContType::P);
    }

    public function __toString() : string
    {
        return implode(PHP_EOL, [
            'Player: ',
            $this->_name,
            '',
            sprintf('Health %s of %s', $this->_health, $this->_health->getMaxValue())
        ]);
    }
}