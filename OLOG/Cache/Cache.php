<?php

namespace OLOG\Cache;

class Cache{
    static protected $storage_arr = array();

    /*
    static public function increment($key)
    {
        // мы не можем корректно обновить значение в статическом кэше - он обновится только на одной машине
        // поэтому удаляем неактуальное значение с тем, чтобы оно если что перечиталось из мемкеша
        unset(self::$storage_arr[$key]);

        return $engine_classname::increment($key);
    }
    */

    static public function set(string $bucket_id, string $key, $value, int $ttl_seconds){
        $local_key = self::localKey($bucket_id, $key);
        self::$storage_arr[$local_key] = $value;

        $bucket = CacheConfig::bucket($bucket_id);
        $bucket->set($key, $value, $ttl_seconds);
    }

    /**
     * Returns false if key not found in cache.
     */
    static public function get(string $bucket_id, string $key){
        $local_key = self::localKey($bucket_id, $key);
        if (isset(self::$storage_arr[$local_key])) {
            return self::$storage_arr[$local_key];
        }

        $bucket = CacheConfig::bucket($bucket_id);
        $value = $bucket->get($key);

        //if ($value !== false) { // why not store false?
        self::$storage_arr[$local_key] = $value;
        //}

        return $value;
    }

    static public function delete(string $bucket_id, string $key){
        $local_key = self::localKey($bucket_id, $key);
        unset(self::$storage_arr[$local_key]);

        $bucket = CacheConfig::bucket($bucket_id);
        return $bucket->delete($key);
    }

    static protected function localKey(string $bucket_id, string $key){
        // use separator for cases like:
        // - bucket 1 key value5
        // - bucket 1value key 5
        // local key must not be the same
        return $bucket_id . '___' . $key;
    }
}