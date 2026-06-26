<?php

namespace App\Config;

class MemoryCacherConfig
{
    public const string DataPath = __DIR__ . '/../../data/filecache/';


    public const string DefaultMemoryCacherMode = 'file';

    const string EntityContext = 'Entity';

    const string WorldContext = 'World_';
    const int EntityRestoreLimit = 1000000;

    // redis
    const string Redis_Scheme = "tcp";
    const string Redis_Host = "localhost";
    const string Redis_Port = "6379";
    const string Redis_Password = "";
    const int Redis_Database = 0;
}