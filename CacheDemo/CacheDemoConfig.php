<?php

namespace CacheDemo;

use OLOG\Cache\BucketMemcache;
use OLOG\Cache\BucketRedis;
use OLOG\Cache\CacheConfig;
use OLOG\Cache\MemcacheServer;
use OLOG\Cache\RedisServer;

class CacheDemoConfig
{
    static public function init(){
        //CacheConfig::setBucket('', new BucketMemcache([new MemcacheServer('127.0.0.1', 11211)]));
        CacheConfig::setBucket('', new BucketRedis([new RedisServer('127.0.0.1', 6379)]));
    }
}