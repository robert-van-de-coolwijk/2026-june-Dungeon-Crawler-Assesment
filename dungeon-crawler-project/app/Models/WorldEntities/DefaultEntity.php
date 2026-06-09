<?php

namespace App\Models\WorldEntities;

class DefaultEntity
{

    public static function Create(): Entity
    {
        return new Entity();
    }

}