<?php

namespace App\Models\WorldEntities;

use App\Models\GameDataTypes\Boolean;
use App\Models\GameDataTypes\Identifier;
use App\Models\GameDataTypes\Resource;
use App\Models\WorldEntities;

/**
 * MUCK systems call this an "exit", describes the relation between two rooms and can be used to travel between them.
 * In game terms, you can traverse from one room to another allowing some freedom with the portal to describe "the mode of transportation" for flavor.
 *
 * portals are ONE way, as:
 * the name of the portal is what you enter, and doesn't make sense when you appear at the other end.
 * If you go north, this portal named north.
 */
class Portal extends WorldEntities\Entity
{

    public ?Identifier $_source = null;

    public ?Identifier $_target = null;

    public function __construct()
    {
        parent::__construct();

    }
}