<?php

namespace OLOG\Cache;


class CacheConfig
{
    /*
    static protected $servers_obj_arr = [];
    static protected $cache_key_prefix = '';
    static protected $engine_class_name = CacheMemcache::class;
    static protected $cache_engine_params_arr = [];
    */

    static protected $buckets_arr = [];

    static public function setBucket(string $bucket_id, BucketInterface $bucket){
        self::$buckets_arr[$bucket_id] = $bucket;
    }

    static public function bucket(string $bucket_id): BucketInterface{
        if (!array_key_exists($bucket_id, self::$buckets_arr)){
            throw new \Exception('No bucket');
        }

        return self::$buckets_arr[$bucket_id];
    }

}