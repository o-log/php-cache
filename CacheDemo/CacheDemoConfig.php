<?php

namespace CacheDemo;

use OLOG\Cache\BucketMemcache;
use OLOG\Cache\CacheConfig;
use OLOG\Cache\MemcacheServer;

class CacheDemoConfig
{
    static public function init(){
        CacheConfig::setBucket('', new BucketMemcache([new MemcacheServer('127.0.0.1', 11211)]));
    }
}