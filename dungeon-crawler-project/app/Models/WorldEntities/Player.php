<?php

namespace App\Models\WorldEntities;

use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\MsgWrap;
use App\Core\Tools;
use App\Models\GameDataTypes\EntityRelationManager;
use App\Models\GameDataTypes\Identifier;
use App\Models\GameDataTypes\UniqueIdentifier;
use App\Models\GameDataTypes\CollectionIdentifier;

class Player extends Creature
{
    public CreatureType $_creatureType = CreatureType::Player;


    public function __construct()
    {
        parent::__construct();
    }

    public function getStateOfThePlayer() : MsgWrap
    {
        $msg = implode(PHP_EOL, [
            $this->_name,
            sprintf('Health %s of %s', $this->health, $this->healthMax),
            sprintf('Room %s', $this?->_insideContainer ?? '[ No room ]'),
        ]);

        return Tools::MsgWrap($msg, ContType::P);
    }

    public function __toString() : string
    {
        return implode(PHP_EOL, [
            'Player: ',
            $this->_name,
            '',
            sprintf('Health %s of %s', $this->health, $this->healthMax)
        ]);
    }




}