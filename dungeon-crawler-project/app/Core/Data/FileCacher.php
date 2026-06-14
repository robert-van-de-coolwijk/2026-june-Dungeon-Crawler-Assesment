<?php

namespace App\Core\Data;

use App\Config\Config;
use App\Config\FileCacherConfig;
use App\Core\Tools;
use App\Models\SingletonPattern;

class FileCacher  extends SingletonPattern{

    protected function __construct() {
        parent::__construct();

        $path = FileCacherConfig::DataPath;

//        echo '<br />Init FileCacher';
//        Tools::debugFilePath($path);
//        echo '<br />Done (Init FileCacher)';
    }

    public function put(string $fContextString, $fData, $fDebug = false) : bool
    {
        $path = $this->getContextPath($fContextString);

        if($fDebug){
            Tools::debugFilePath($path);
        }

        $serializedData = json_encode($fData);

        $success = (bool)file_put_contents($path, $serializedData);

        if($fDebug){
            Tools::debug('FileCacher.put() - Write file ok<br />' . $path);
        }

        return $success;
    }

    public function exists(string $fContextString) : bool
    {
        $path = $this->getContextPath($fContextString);

        return file_exists($path);
    }

    public function get(string $fContextString) : mixed
    {
        $path = $this->getContextPath($fContextString);
        //Tools::debugFilePath($path);

        $serializedData = file_get_contents($path);

        $fData = json_decode($serializedData);

        return $fData;
    }

    private function getContextPath(string $fContextString) : string
    {
        $crc = crc32($fContextString);
        $path = FileCacherConfig::DataPath . '/' . $fContextString . '_' . $crc . '.json';

        return $path;
    }

    public static function getInstance() : FileCacher {
        return parent::getInstance();
    }

}