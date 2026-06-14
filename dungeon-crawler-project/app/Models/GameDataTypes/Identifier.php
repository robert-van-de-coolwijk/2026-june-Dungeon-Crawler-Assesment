<?php

namespace App\Models\GameDataTypes;

use App\Core\Tools;
use App\Models\Game;
use App\Models\WorldEntities\World;



/**
 * This value stores the ID of one Entity and is mandatory for an entity to exist
 */
class Identifier extends IdRef
{
    public const int ID_START_NUMBER = 1;


    private function __construct() {
        parent::__construct();

        $world = Game::getInstance()->getWorld();

        $id = $world->getHighestIdentifier();

        $id = $id < self::ID_START_NUMBER ?
            self::ID_START_NUMBER :
            ++$id;

        //Tools::debug($id);

        $this->data = self::ID_PREFIX . $id;

        //Tools::debug($this->data);

        $world->setHighestIdentifier($id);

    }

    public static function getNewIdentifier(): Identifier {
        return new Identifier();
    }

    public function validate(string $input) : bool {
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