<?php

namespace App\Models\GameDataTypes;

//@todo RC consider using this class and implement properly

/**
 * The lock class (when implemented) describes if an entity or property can be changed.
 * - TRUE everyone (both players and actors) can change the value
 * - FALSE is read only
 * - Player ID
 *
 * This is a GameDataType that is implicitly tied to its function.
 */
class Lock extends GameDataType
{
    public string $data;

    /**
     * @throws \Exception
     */
    public function __construct(string $value = 'true')
    {
        $this->set($value);
    }

    public function get(): string
    {
        return $this->data;
    }

    /**
     * @throws \Exception
     */
    public function set(string $value): boolean
    {
        if(!$this->valid($value)){
            throw new \Exception("Invalid value '$value' for lock");
        }

        $this->data = $value;

        return true;
    }

    public function valid($value): bool {
        // if true, false or valid player number (#number that isPlayer = true), accept value
            // return true
        // else
            // throw user exception saying, you can not change

        return true;
    }
}


