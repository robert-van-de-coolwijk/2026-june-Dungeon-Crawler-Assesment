<?php

namespace DungeonCrawlerCLI;

class CliPrinter
{
    public function out($message) : void
    {
        echo $message;
    }

    public function newline() : void
    {
        $this->out(PHP_EOL);
    }

    public function display(array|string $message) : void
    {
        if(is_array($message)) {
            foreach ($message as $line) {
                $this->display($line);
            }
            return;
        }

        $this->out($message);
        $this->newline();
    }
}