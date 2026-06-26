<?php

namespace App\Core\Data\MemoryCacher;

use App\Config\MemoryCacherConfig;
use App\Core\Data\FileCacher;
use stdClass;

/**
 * Uniform memory sharing class supports easily choosing what type of backend to use
 */
abstract class MemoryCacher
{

    const string SnapshotPreFix = 'Snapshot_';
    const string MemoryProfilerPreFix = 'MemoryProfiler_';

    const string MemoryCacherMode_file = 'file';
    #const MemoryCacherMode_file_on_ramdisk = 'ramfile';
    const string MemoryCacherMode_apc = 'apc';
    const string MemoryCacherMode_apcu = 'apcu';
    const string MemoryCacherMode_redis = 'redis';

    const false DebugMode = false;
    const true TraceMemoryUsage = true;

    protected function __construct()
    {
//        if(self::TraceMemoryUsage){
//            $this->MemoryTraceArray = array();
//        }

    }

    public function put($fContextString, $fData, $fDebug = true) : void
    {

        if (self::TraceMemoryUsage) {
            $fileCacher = new FileCacher();
            $memoryProfilerKey = self::getMemoryProfilerKey();
            $memoryTraceArray = new stdClass();

            if ($fileCacher->exists($fContextString)) {
                $memoryTraceArray = $fileCacher->get($memoryProfilerKey);
            }

            $serializedData = json_encode($fData);
            $memoryTraceArray->{$fContextString} = mb_strlen($serializedData) * 8;

            $fileCacher->put($memoryProfilerKey, $memoryTraceArray);
        }

    }

    public function getMemoryProfilerReport() : string
    {
        $fileCacher = new FileCacher();
        $memoryProfilerKey = self::getMemoryProfilerKey();
        $data = $fileCacher->get($memoryProfilerKey);

        return '<pre>' . var_export($data, true) . '</pre>';
    }

    static public function getSupportMemoryCacherModes() : array
    {
        return array(
            self::MemoryCacherMode_file,
            //self::MemoryCacherMode_shmop,
            self::MemoryCacherMode_apc
        );
    }

    static public function getMemoryCacherObject() : MemoryCacherMode_file|MemoryCacherMode_apc|MemoryCacherMode_apcu|MemoryCacherMode_redis
    {
        $memoryCacheObject = null;

        $cacheMode = MemoryCacherConfig::DefaultMemoryCacherMode;

        switch ($cacheMode)
        {
            case self::MemoryCacherMode_apc:
                $memoryCacheObject = new MemoryCacherMode_apc();
                break;

            case self::MemoryCacherMode_apcu:
                $memoryCacheObject = new MemoryCacherMode_apcu();
                break;

            case self::MemoryCacherMode_redis:
                $memoryCacheObject = new MemoryCacherMode_redis();
                break;

            case self::MemoryCacherMode_file:
            default:
                $memoryCacheObject = new MemoryCacherMode_file();
        }


        return $memoryCacheObject;
    }

    static protected function serialize($fData): string
    {
        return serialize($fData);
    }

    static protected function unserialize($fData): mixed
    {
        return unserialize($fData);
    }

    static protected function getContextPath($fContextString): string
    {
        $crc = crc32($fContextString);
        $path = $fContextString . '_' . $crc;

        return $path;
    }

    static protected function snapshotToFileCacher(MemoryCacher $fMemcached, $fKey): void
    {
        $fileCacher = new FileCacher();
        $snapshotKey = self::getSnapshotKey($fKey);

        $data = $fMemcached->get($fKey);

        $fileCacher->put($snapshotKey, $data);
    }

    static protected function restoreFromFileCacher(MemoryCacher $fMemcached, $fKey): void
    {
        $fileCacher = new FileCacher();
        $snapshotKey = self::getSnapshotKey($fKey);

        $data = $fileCacher->get($snapshotKey);

        $fMemcached->put($fKey, $data);
    }

    static protected function getSnapshotKey($fKey): string
    {
        return self::SnapshotPreFix . $fKey;
    }

    static protected function getMemoryProfilerKey(): string
    {
        return self::MemoryProfilerPreFix;
    }

}