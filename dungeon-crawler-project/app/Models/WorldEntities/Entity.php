<?php

namespace App\Models\WorldEntities;

use App\Models\GameDataTypes\GameDataType;
use App\Models\GameDataTypes\Identifier;
use App\Models\GameDataTypes\ShortText;
use App\Models\GameDataTypes\Text;
use Exception;

class Entity {
    protected Identifier $_id; // This number can become LARGE. A lot of entities like rooms will have a minimum of 4 entities associated with them.

    protected ShortText $_name;

    protected Text $_description;

    protected function __construct()
    {
        $this->_id = Identifier::getNewIdentifier();

        $this->_name = new ShortText();
        $this->_description = new ShortText();
    }

    /**
     * @throws Exception
     */
    protected function getSetSanityCheck(string $name, string $method) : bool
    {

        // The order of these checks is VERY important.
        // The next check will blindly expect things to exist so it RELIES on checks above it.
        switch ($name) {
            case !property_exists($this, $name):
                throw new Exception("Property '{$name}' does not exist");

            case is_null($this->{$name}):
                throw new Exception("Property '{$name}' is unset, please report this error for entity ID #{$this->id}");

            case $method == 'get':
                return method_exists($this, '__toString');

            case $method == 'set':
                return method_exists($this, 'set');

            default:
                throw new Exception("Something went horribly wrong. If you can read this message, report that property '{$name}' on entity ID #{$this->id} caused an ERROR the game was not designed for.");
        }
    }

    protected static function getPropKey($name) : string {
        return "_$name";
    }

    /**
     * @throws Exception
     */
    public function __get(string $name)
    {
        $propName = self::getPropKey($name);

        if($this->getSetSanityCheck($propName, 'get')){
            return $this->{$propName}->__toString();
        }
    }

    /**
     * @throws Exception
     */
    public function __set(string $name, string $value)
    {
        $propName = self::getPropKey($name);

        if($this->getSetSanityCheck($propName, 'set')){
            return $this->{$propName}->set($value);
        }
    }

    public function getIdAsNumber() : int
    {
        return $this->_id->getAsNumber();
    }
}