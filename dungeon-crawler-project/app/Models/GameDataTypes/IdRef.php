<?php

namespace App\Models\GameDataTypes;

use App\Core\Tools;
use App\Models\Game;
use App\Models\WorldEntities\World;



/**
 * This value stores the ID of one Entity as a reference
 * it is not implicit connection and is optional
 */
class IdRef extends Text
{
    public const string ID_PREFIX = '#';
    protected const int maxLength = 255;

    public const string Unset = '[unset]';

    public function __construct() {
        parent::__construct();

        $this->data = self::Unset;
    }

    public function validate(string $input) : bool {
        // only valid for idRef
        if(strcmp($input, self::Unset)){
            return true;
        }

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