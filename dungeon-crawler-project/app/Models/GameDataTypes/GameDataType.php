<?php

namespace App\Models\GameDataTypes;

abstract class GameDataType
{
    /**
     * mechanism to lock properties to specific states to indicate if they can be changed.
     * true = an actor may change the values,
     * false = read only, no actor can change
     * #key = if that key is or is in the inventory of the actor you can change the property
     */
    //public Lock $lock; // @todo RC


    public function __construct(){
        //$this->lock = new Lock(); // @todo RC Find a way to initiate objects in a way that lets me finely define all of the default values for said properties without adding to much complexity general.
    }

    public abstract function get() : string;
    public abstract function set(string $input) : bool;

}