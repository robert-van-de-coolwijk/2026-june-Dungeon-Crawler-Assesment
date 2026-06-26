<?php


namespace App\Core\Data\MemoryCacher;

use App\Core\Data\FileCacher;

/**
 * Memory cacher through file read and write
 * So it is NOT a memory cacher model by design!
 * However, it is the most accessible, easiest to set up on default servers.
 */
class MemoryCacherMode_file extends MemoryCacher implements MemoryCacherInterface
{

    private $fileCacherObject;

    const ContextPrefix = 'MCF_';

    public function __construct()
    {
        parent::__construct();

        $this->fileCacherObject = new FileCacher();
    }

    public function put($fContextString, $fData, $fDebug = true): void
    {
        $contextPath = $this->prefixContextPath($fContextString);
        $this->fileCacherObject->put($contextPath, $fData, $fDebug);
    }

    public function exists($fContextString): bool
    {
        $contextPath = $this->prefixContextPath($fContextString);

        return $this->fileCacherObject->exists($contextPath);
    }

    public function get($fContextString) : mixed
    {
        $contextPath = $this->prefixContextPath($fContextString);

        return $this->fileCacherObject->get($contextPath);
    }

    private function prefixContextPath($fContextString): string
    {
        $path = self::ContextPrefix . $fContextString;

        return $path;
    }

}
