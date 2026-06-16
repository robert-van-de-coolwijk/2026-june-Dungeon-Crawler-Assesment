<?php

namespace DungeonCrawlerCLI;


use App\Controllers\Main;
use App\Core\Tools;

class App
{
    protected bool $continueMode = false;
    protected CliPrinter $printer;
    protected CliReader $reader;

    protected Main $main; // @todo EVIL, there must be a better way to do this. Consider changing this into an API or ANYTHING else for that matter.

    protected $registry = [];

    public function __construct()
    {
        $this->printer = new CliPrinter();
        $this->reader = new CliReader();


        $this->main = new Main();
        $this->main->start();

        /// register valid commands (client side) \\\

        //@todo RC

        // game (server) commands
        $this->registerCommand("commands", "genericCommandHandler");

        $this->registerCommand("time", "genericCommandHandler");
        $this->registerCommand("state", "genericCommandHandler");

        $this->registerCommand("save", "genericCommandHandler");
        $this->registerCommand("restore", "genericCommandHandler");

        $this->registerCommand("player", "genericCommandHandler");
        $this->registerCommand("init", "genericCommandHandler");

        $this->registerCommand("look", "genericCommandHandler");

        $this->registerCommand("move", "genericCommandHandler");
        $this->registerCommand("north", "genericCommandHandler");
        $this->registerCommand("east", "genericCommandHandler");
        $this->registerCommand("south", "genericCommandHandler");
        $this->registerCommand("west", "genericCommandHandler");

        $this->registerCommand("fight", "genericCommandHandler");




        // client commands
        $this->registerCommand("hello", function (array $params) {
            $name = isset ($params[0]) ? $params[0] : "World";
            $this->getPrinter()->display("Hello $name!!!");
        });

        $this->registerCommand("help", function (array $params) {
            $this->getPrinter()->display([
                "usage: command [ params ]",
                "Type commands to get a list of all available game commands."
            ]);
        });

        $this->registerCommand("exit", function (array $params) {
            $this->setContinuesMode(false);
            $this->getPrinter()->display("Exiting application");
        });
    }

    public function getPrinter(): \DungeonCrawlerCLI\CliPrinter
    {
        return $this->printer;
    }


    /// command logic \\\


    public function genericCommandHandler(string $commandName, array $params) : void
    {
        $message = $this->main->command($commandName, $params);

        $this->getPrinter()->display($message);
    }

    public function registerCommand($commandName, $callable): void
    {
        $this->registry[$commandName] = $callable;
    }

    protected function getCommand($command)
    {
        return isset($this->registry[$command]) ? $this->registry[$command] : null;
    }

    public function runCommand(string $commandName = "help", array $params = []) : bool
    {
        $command = $this->getCommand(strtolower($commandName));

        if ($command === null) {
            $this->getPrinter()->display(sprintf('ERROR: Command "%s" not found.', $commandName));

            return false;
        }else if(is_callable($command)){
            call_user_func($command, $params);
        }else if(is_string($command) && method_exists($this, $command)){
            $this->{$command}($commandName, $params);
        }else{
            $this->getPrinter()->display(sprintf('ERROR: Command "%s" is registered, but could not be executed.', $commandName));
        }


        return true;
    }

    public function runCommandFromInput(): void
    {
        $this->reader->read();

        $line = $this->reader->popLine();

        if(is_string($line) && strlen($line) > 0){
            $line = explode(" ", $line);
            $commandName = array_splice($line, 0, 1)[0];

//            Tools::debug($commandName);
//            Tools::debug($line);

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