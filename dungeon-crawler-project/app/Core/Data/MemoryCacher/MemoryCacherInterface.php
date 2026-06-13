<?php


namespace App\Core\Data\MemoryCacher;

interface MemoryCacherInterface
{

    public function put($fContextString, $fData, $fDebug = true);

    public function exists($fContextString);

    public function get($fContextString);

}