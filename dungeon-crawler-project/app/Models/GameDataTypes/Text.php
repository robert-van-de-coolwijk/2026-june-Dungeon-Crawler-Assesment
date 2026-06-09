<?php

namespace App\Models\GameDataTypes;

class Text extends GameDataType
{

    protected const int maxLength = 4096;

    protected string $data;


    public function __construct()
    {
    }

    public function __toString() : string
    {
        return $this->get();
    }

    public function get() : string
    {
        return $this->data;
    }

    // @todo RC make it so when a string is being assigned to this data type, it instead just ingests it the normal way way through the setter if possible.

    public function set(string $input) : Boolean
    {
        if($this->validate($input)){
            $this->data = $input;

            return true;
        }

        return false;
    }

    public function validate(string $input) : Boolean {
        if(strlen($input) > self::maxLength){
            // handle error
            return false;
        }

        return true;
    }
}