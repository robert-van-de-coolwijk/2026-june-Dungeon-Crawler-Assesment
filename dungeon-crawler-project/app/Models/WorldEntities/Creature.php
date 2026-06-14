<?php

namespace App\Models\WorldEntities;



use App\Core\Tools;
use App\Models\GameDataTypes\Resource;
use Exception;

class Creature extends Container
{
    public CreatureType $_creatureType; //@todo RC expand or remove, the idea is to get a basic sense of the creature is and split up all kinds of things (behaviour, attacks, initial alignment, property growth) through this, but is currently not in use.

    // Inventory == $this->contents


    /// RESOURCES \\\
    public Resource $_health;
    public Resource $_healthMax;

    /// ATTRIBUTES \\\


    public function __construct()
    {
        parent::__construct();

        $this->_creatureType = CreatureType::Unset;

        $this->_health = new Resource(30); // @todo RC calculate this from default attributes
        $this->_healthMax = new Resource(30);
    }

    public function __set(string $name, $value)
    {
        switch ($name)
        {
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

    public function isAlive() : bool {
        return $this->_health->getAsNumber() > 0;
    }

    public function healCreature(int $healAmount) : void
    {
        $this->health = Tools::ClampNumber($this->_health->getAsNumber() + $healAmount, 0, $this->_healthMax->getAsNumber());
    }

    public function damageCreature(int $damage) : void
    {
        $this->health = Tools::ClampNumber($this->_health->getAsNumber() - $damage, 0, $this->_healthMax->getAsNumber());
    }

}