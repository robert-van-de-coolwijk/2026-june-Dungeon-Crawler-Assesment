<?php

namespace App\Models\WorldEntities;


use App\Models\GameDataTypes\Collection;
use App\Models\GameDataTypes\Resource;
use Exception;

class Creature extends Entity
{

    public CreatureType $_creatureType;

    public Collection $_inventory;

    /// RESOURCES \\\
    public Resource $_health;

    /// ATTRIBUTES \\\


    public function __construct()
    {
        parent::__construct();

        $this->_creatureType = CreatureType::Unset;

        $this->_inventory = new Collection();

        $this->_health = new Resource();
    }

    public function __set(string $name, $value)
    {

        if(!strcmp($name, 'creatureType')){
            $result = parent::__set($name, $value);

            if($result === false){
                // @todo RC test and handle error
            } else if ( $result === true) {
                // this if good flow
                true;
            } else {
                // this should never happen and the exception is just there to report if it does
                throw new Exception("Programming error, entity #{$this->id} property {$name}, on setting the value '$value' returned a non boolean value.");
            }

            return; //@todo RC decide if we keep void returns to prevent continuation of code execution OR use something else for that purpose OR put something useful like the result of the set as the return value
        }

        // cast to lowercase
        $value = strtolower($value);

        if(enum_exists(CreatureType::{$value})) {
            $this->creatureType = CreatureType::{$value};
        } else {
            throw new Exception("Creature type '$value' ");
        }

    }
}