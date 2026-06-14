<?php

namespace App\Models\WorldEntities;


use App\Core\Tools;
use App\Models\GameDataTypes\Collection;
use App\Models\GameDataTypes\Identifier;
use App\Models\GameDataTypes\Resource;
use Exception;

class Creature extends Container
{
    public CreatureType $_creatureType;

    // Inventory == $this->contents


    /// RESOURCES \\\
    public Resource $_health;

    /// ATTRIBUTES \\\


    public function __construct()
    {
        parent::__construct();

        $this->_creatureType = CreatureType::Unset;

        $this->_health = new Resource(10); // @todo RC calculate this from default attributes
    }

    public function __set(string $name, $value)
    {
        switch ($name) {
            case strcmp($name, 'creatureType'):

                if(enum_exists(CreatureType::{$value})) {
                    $this->creatureType = CreatureType::{$value};
                } else {
                    throw new Exception("Creature type '$value' is invalid");
                }
                break;

            default:
                parent::__set($name, $value);

        }

    }
}