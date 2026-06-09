<?php

namespace App\Models\GameDataTypes;

use DungeonCrawlerCLI\CliReader;

abstract class GameDataType
{

    public abstract function __construct();

    public abstract function get() : string;
    public abstract function set(string $input) : Boolean;

}