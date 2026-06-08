<?php

namespace DungeonCrawlerCLI;


enum CliReaderMode: string
{
    case StdInput = 'stdin';
    case StdError = 'stderr';
}