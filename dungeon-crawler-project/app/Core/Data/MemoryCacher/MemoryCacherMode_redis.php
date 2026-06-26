<?php


namespace App\Core\Data\MemoryCacher;

class MemoryCacherMode_redis extends MemoryCacher implements MemoryCacherInterface
{

    private $apcObject;

    protected function __construct()
    {
        parent::__construct();
    }


    public function put($fContextString, $fData, $fDebug = true) : void
    {
        $key = self::getContextPath($fContextString);

        //$serializedData = json_encode($fData);
        $serializedData = self::serialize($fData);

        apc_store($key, $serializedData);
    }

    public function exists($fContextString): array|bool
    {
        $key = self::getContextPath($fContextString);

        return apc_exists($key);
    }

    public function get($fContextString)
    {
        $key = self::getContextPath($fContextString);
//        $pcshmObj = $this->initShmop($key);

        $serializedData = apc_fetch($key);


        $serializedDataTemp = trim($serializedData);


        $data = self::unserialize($serializedDataTemp);

        if (self::DebugMode == true) {
            echo '<pre style="border: 1px solid red;">';
            echo var_dump($serializedData);
            echo '</pre>';

            echo '<pre style="border: 1px solid blue;">';
            echo var_dump($serializedDataTemp);
            echo '</pre>';

            echo '<pre style="border: 1px solid green;">';
            echo var_dump($data);
            echo '</pre>';
        }

        return $data;
    }

}
