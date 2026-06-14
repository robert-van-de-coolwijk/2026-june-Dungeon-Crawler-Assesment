<?php

namespace App\Models\GameDataTypes;

/**
 * Prop is a reverse reference
 */
class Collection extends GameDataType
{

    // @todo RC consider introducing a $allowType(s) to check if a certain entity is allowed inside this collection


    protected string $collectionName;

    public function __construct(string $collectionName)
    {
        parent::__construct();

        $this->collectionName = $collectionName;
    }


    public function get(): string
    {
        // TODO: Implement get() method.
        throw new \Exception("No implemented");
    }

    public function set(string $input): bool
    {
        // TODO: Implement set() method.
        throw new \Exception("No implemented");
    }
}