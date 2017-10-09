<?php

namespace OLOG\Cache;

class Cache{
    static public function set($bucket_id, $key, $value, $ttl){
        
    }

    /**
     * Returns false if key not found in cache.
     * @param type $bucket_id
     * @param type $key
     * @return boolean
     */
    static public function get($bucket_id, $key){
        return false;
    }
}