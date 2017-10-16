<?php

namespace OLOG\Cache;

class Cache{
    static public function set(string $bucket_id, string $key, $value, int $ttl_seconds){
        $bucket = CacheConfig::bucket($bucket_id);
        $bucket->set($key, $value, $ttl_seconds);
    }

    /**
     * Returns false if key not found in cache.
     */
    static public function get(string $bucket_id, string $key){
        $bucket = CacheConfig::bucket($bucket_id);
        return $bucket->get($key);
    }

    static public function delete(string $bucket_id, string $key){
        $bucket = CacheConfig::bucket($bucket_id);
        return $bucket->delete($key);
    }
}