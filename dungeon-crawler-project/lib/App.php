<?php

namespace DungeonCrawlerCLI;


use DungeonCrawlerCLI\CliPrinter;

class App
{
    protected bool $continueMode = false;
    protected CliPrinter $printer;
    protected CliReader $reader;

    protected $registry = [];

    public function __construct()
    {
        $this->printer = new CliPrinter();
        $this->reader = new CliReader();


        $this->registerCommand('hello', function (array $argv) {
            $name = isset ($argv[1]) ? $argv[1] : "World";
            $this->getPrinter()->display("Hello $name!!!");
        });

        $this->registerCommand('help', function (array $argv) {
            $this->getPrinter()->display("usage: minicli hello [ your-name ]");
        });

        $this->registerCommand('exit', function (array $argv) {
            $this->setContinuesMode(false);
            $this->getPrinter()->display("Exiting application");
        });
    }

    public function getPrinter(): \DungeonCrawlerCLI\CliPrinter
    {
        return $this->printer;
    }

    public function registerCommand($name, $callable): void
    {
        $this->registry[$name] = $callable;
    }

    protected function getCommand($command)
    {
        return isset($this->registry[$command]) ? $this->registry[$command] : null;
    }

    public function runCommand(string $commandName = "help", array $params = []) : bool
    {
        $command = $this->getCommand(strtolower($commandName));

        if ($command === null) {
            $this->getPrinter()->display("ERROR: Command \"$commandName\" not found.");

            return false;
        }

        call_user_func($command, $params);

        return true;
    }

    public function runCommandFromInput(): void
    {
        $this->reader->read();

        $line = $this->reader->popLine();

        if(is_string($line) && strlen($line) > 0){
            $line = explode(" ", $line);
            $commandName = array_splice($line, 0, 1)[0];

            $this->runCommand($commandName, $line);
        }
    }

    public function setContinuesMode($continueMode = true): void
    {
        $this->continueMode = $continueMode;
    }

    public function isContinueMode(): bool
    {
        return $this->continueMode;
    }


}