<?php

namespace DungeonCrawlerCLI;

class App
{
    public function runCommand(array $argv)
    {
        $name = "Earth";
        if (isset($argv[1])) {
            $name = $argv[1];
        }

        echo "Hello $name!!!\n";
    }
}