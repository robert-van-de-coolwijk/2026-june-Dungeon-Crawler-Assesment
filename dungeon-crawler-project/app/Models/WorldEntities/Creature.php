<?php

namespace App\Models\WorldEntities;


use App\Models\GameDataTypes\Collection;
use Exception;

class Creature extends Entity
{
    public Collection $inventory;

    public CreatureType $creatureType = CreatureType::Unset;

    public Resource $health;

    public function __construct()
    {
        $this->inventory = new Collection();
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