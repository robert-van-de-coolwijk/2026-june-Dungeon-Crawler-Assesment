<?php

namespace DungeonCrawlerCLI;

use App\Core\Tools;
use Exception;
use http\Encoding\Stream;


class CliReader
{

    protected $filePointer;

    protected CliReaderMode $mode;

    protected string $readBuffer = '';

    protected array $lineBuffer = [];

    /**
     * @param CliReaderMode $mode
     * @throws Exception
     */
    public function __construct(CliReaderMode $mode = CliReaderMode::StdInput) {
        $this->mode = $mode;


        $fp = fopen(sprintf('php://%s', $mode->value), 'r');

        if(get_resource_type($fp) == 'stream') {
            $this->filePointer = $fp;
        } else {
            throw new Exception('Could not open stream on mode: ' . $mode->value);
        }
    }

    /**
     * Reads the input and splits it into lines after a newline
     * @return void
     */
    public function read(){
        // append current content to readBuffer
        $this->readBuffer .= fgets($this->filePointer);

        // see if a line break is in there
        if(str_contains($this->readBuffer, "\n")){

            // put everything except the last line into the lineBuffer
            $lines = explode("\n", $this->readBuffer);

            while(count($lines) > 1){
                $this->lineBuffer[] = array_splice($lines, 0, 1);
            }
        }
    }

    /**
     * Pops the oldest line from the lineBuffer or NULL if buffer is empty
     * @return ?string
     */
    public function popLine() : ?string {
        return (
            count($this->lineBuffer) > 0 ?
            array_pop($this->lineBuffer)[0] :
            null
        );
    }
}