<?php

namespace App\Models\WorldEntities;

use App\Models\GameDataTypes\Boolean;
use App\Models\GameDataTypes\Identifier;
use App\Models\WorldEntities;

/**
 * MUCK systems call this an "exit", describes the relation between two rooms and can be used to travel between them.
 * In game terms, you can traverse from one room to another allowing some freedom with the portal to describe "the mode of transportation" for flavor
 * and dictates bidirectional by default or one way travel.
 */
class Portal extends WorldEntities\Entity
{
    public Boolean $isOneWay;

    public Identifier $source;

    public Identifier $target;

    public function __construct(){
        parent::
    }

}