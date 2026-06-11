<?php

namespace App\Models\GameDataTypes;

abstract class GameDataType
{
    public Lock $lock;


    public function __construct(){
        $this->lock = new Lock(); // @todo RC Find a way to initiate objects in a way that lets me finely define all of the default values for said properties without adding to much complexity general.
    }

    public abstract function get() : string;
    public abstract function set(string $input) : bool;

}