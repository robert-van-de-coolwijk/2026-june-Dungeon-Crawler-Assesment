<?php


namespace App\Core\Data\MemoryCacher;

use App\Config\MemoryCacherConfig;
use Predis\Client as PredisClient;

/**
 * Memory cacher through redis
 * https://redis.io/tutorials/develop/php/
 */
class MemoryCacherMode_redis extends MemoryCacher implements MemoryCacherInterface
{

    private PredisClient $redisClient;

    /**
     * @throws \Exception
     */
    protected function __construct()
    {
        parent::__construct();

        $redis = new PredisClient([
            'scheme'   => MemoryCacherConfig::Redis_Scheme,
            'host'     => MemoryCacherConfig::Redis_Host,
            'port'     => MemoryCacherConfig::Redis_Port,
            'password' => MemoryCacherConfig::Redis_Password,
            'database' => 0,
        ]);

        if (!$redis->ping()) {
            throw new \Exception(sprintf(
                "Could not initialize MemoryCacher with Redis on %s:%s",
                MemoryCacherConfig::Redis_Host,
                MemoryCacherConfig::Redis_Port
            ));
        }

        $this->redisClient = $redis;
    }


    public function put($fContextString, $fData, $fDebug = true) : void
    {
        $key = self::getContextPath($fContextString);

        $serializedData = self::serialize($fData);

        $this->redisClient->set($key, $serializedData);
    }

    public function exists($fContextString): bool
    {
        $key = self::getContextPath($fContextString);

        return apc_exists($key);
    }

    public function get($fContextString) : mixed
    {
        $key = self::getContextPath($fContextString);

        $serializedData = $this->redisClient->get($key);


        $serializedDataTemp = trim($serializedData);


        $data = self::unserialize($serializedDataTemp);

        if (self::DebugMode) {
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
