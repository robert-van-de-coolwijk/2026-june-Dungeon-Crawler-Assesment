<?php

namespace App\Models\WorldEntities;

use App\Models\GameDataTypes\Boolean as BooleanAlias;
use App\Models\GameDataTypes\GameDataType;

class Player extends Creature
{
    public CreatureType $creatureType = CreatureType::Player;
}