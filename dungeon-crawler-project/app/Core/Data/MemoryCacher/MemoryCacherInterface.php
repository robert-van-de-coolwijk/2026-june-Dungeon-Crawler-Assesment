<?php


namespace App\Core\Data\MemoryCacher;

interface MemoryCacherInterface
{

    public function put($fContextString, $fData, $fDebug = true) : void;

    public function exists($fContextString) : bool;

    public function get($fContextString) : mixed;

}