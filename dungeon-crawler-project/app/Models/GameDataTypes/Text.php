<?php

namespace App\Models\GameDataTypes;

use App\Core\Tools;

class Text extends GameDataType
{

    protected const int maxLength = 4096;
    protected const string Unset = '';

    protected string $data = '';


    public function __construct()
    {
        parent::__construct();
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

    public function set(string $input) : bool
    {
        if($this->validate($input)){
            $this->data = $input;

            return true;
        }

        return false;
    }

    public function validate(string $input) : bool {
        if(strlen($input) > self::maxLength){
            throw new \Exception(sprintf('Value for property is to long, %s chars of %s maximum allowed: "%s"', strlen($input), self::maxLength, $input));
        }

        return true;
    }

    public function isUnset() : bool {
        return strcmp(self::Unset, $this->data);
    }
}