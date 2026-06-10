<?php

namespace App\Models\WorldEntities;

use App\Models\GameDataTypes\GameDataType;
use App\Models\GameDataTypes\Identifier;
use App\Models\GameDataTypes\ShortText;
use App\Models\GameDataTypes\Text;
use Exception;

class Entity {
    protected Identifier $id; // This number can become LARGE. A lot of entities like rooms will have a minimum of 4 entities associated with them.

    protected ShortText $name;
    protected Text $description;

    protected function __construct()
    {
        $this->id = Identifier::getNewIdentifier();
    }

    protected function getSetSanityCheck($name, $method)
    {

        // The order of these checks is VERY important.
        // The next check will blindly expect things to exist so it RELIES on checks above it.
        switch ($name) {
            case !isset($this->{$name}):
                throw new Exception("Property '{$name}' does not exist");

            case is_null($this->{$name}):
                throw new Exception("Property '{$name}' is unset, please report this error for entity ID #{$this->id}");

            case 'id':
                return (string)$this->id;

            case $method == 'get':
                return method_exists($this, '__toString');

            case $method == 'set':
                return method_exists($this, 'set');

            default:
                throw new Exception("Something went horribly wrong. If you can read this message, report that property '{$name}' on entity ID #{$this->id} caused an ERROR the game was not designed for.");
        }
    }

    public function __get(string $name)
    {
        if($this->getSetSanityCheck($name, 'get')){
            return $this->{$name}->__toString();
        }

    }

    public function __set(string $name, $value)
    {

        if($this->getSetSanityCheck($name, 'set')){
            return $this->{$name}->set($value);
        }

    }
}