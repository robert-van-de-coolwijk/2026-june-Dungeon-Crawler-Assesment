<?php

namespace App\Models\GameDataTypes;

use App\Models\WorldEntities\World;

/**
 * This value stores the ID of one Entity
 */
class Identifier extends Text
{
    public const int ID_START_NUMBER = 1;
    public const string ID_PREFIX = '#';
    protected const int maxLength = 255;

    private function __construct() {
        parent::__construct();

        $id = World::getInstance()->getHighestIdentifier();

        $id = $id < self::ID_START_NUMBER ?
            self::ID_START_NUMBER :
            $id++;

        $this->set(sprintf('%s%i', self::ID_PREFIX, $id));

    }

    public static function getNewIdentifier(): Identifier {
        return new Identifier();
    }

    public function validate(string $input) : Boolean {
        if(strlen($input) <= 1){
            return false;
        }

        if(!str_starts_with($input, self::ID_PREFIX)){
            return false;
        }

        if(is_numeric(substr($input, 1))){
            return false;
        }

        // all checks passed
        return true;
    }



    public function getAsNumber() : int
    {
        return (int)substr($this->data, 1);
    }

}