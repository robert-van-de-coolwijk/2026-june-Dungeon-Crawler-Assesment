<?php

namespace App\Config;

class MemoryCacherConfig
{

    // redis
    const string Redis_Scheme = "tcp";
    const string Redis_Host = "127.0.0.1";
    const string Redis_Port = "6379";
    const string Redis_Password = "";
    const int Redis_Database = 0;


    /// INTERNAL SETTINGS \\\
    public const string DataPath = __DIR__ . '/../../data/filecache/';

    // fallback method
    public const string DefaultMemoryCacherMode = 'file';

    const string EntityContext = 'Entity';

    const string WorldContext = 'World_';

    // protects the (PHP) server from memory exhaustion and infinite loops caused by issues during restoring the game state
    const int EntityRestoreLimit = 1000000;

}