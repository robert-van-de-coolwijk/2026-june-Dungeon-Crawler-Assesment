<?php

namespace App\Models\WorldEntities;

/**
 * @todo RC Implement + use OR remove. Experimental class to create entities with default values (as the current implementation relies on magic properties that can not be set on creation having to rely on constructors which violates the simplicity principle there. Preferably centralized atleast the logic here and maybe also data. Depends on how good it works.
 */
class DefaultEntity
{

    public static function Create(): ?Entity
    {
        return null; // new Entity();
    }

}