<?php

namespace App\Config;

class FileCacherConfig
{
    public const DataPath = __DIR__ . '/../../data/filecache/';


    public const DefaultMemoryCacherMode = 'file';

    const EntityContext = 'Entity';

    const WorldContext = 'World_';
    const EntityRestoreLimit = 1000000;
}