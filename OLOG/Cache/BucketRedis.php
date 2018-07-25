<?php
declare(strict_types=1);

namespace OLOG\Cache;

/**
 * Class BucketRedis
 * TODO: rewrite
 * @package OLOG\Cache
 */
class BucketRedis implements BucketInterface
{
    protected $cache_key_prefix;
    protected $servers = [];
    protected $redis = NULL;

    public function __construct($servers, $cache_key_prefix = "default"){
        $this->servers = $servers;
        $this->cache_key_prefix = $cache_key_prefix;
    }

    public function set($key, $value, $ttl_secs)
    {
        $redis_connection_obj = $this->connection(); // do not check result - already checked
        if (!$redis_connection_obj) {
            return false;
        }

        if ($ttl_secs == -1) {
            $ttl_secs = 60;
        }

        if ($ttl_secs < 0) {
            $ttl_secs = 0;
        }

        if ($ttl_secs == 0) {
            return true;
        }

        $full_key = $this->cacheKey($key);
        $value_ser = serialize($value);

        if ($ttl_secs > 0) {
            $mcs_result = $redis_connection_obj->setex($full_key, $ttl_secs, $value_ser);
        } else {
            $mcs_result = $redis_connection_obj->set($full_key, $value_ser);
        }

        if (!$mcs_result) {
            return false;
        }

        return true;
    }

    public function increment($key)
    {
        // TODO: implement
        throw new \Exception('redis increment not implemented');
        // инкремент сейчас не поддерживается
        // что надо сделать:
        // 1. если такого ключа еще нет - редис создает новый со значением 1, при этом у нас все значения должны быть
        // сериализованные, а это будет не сериализованное. нужно запретить создавать ключ если если его при инкременте?
        // 2. перед инкрементом десериализовать, а потом сериализовать обратно

        /*
        $mc = self::getRedisConnectionObj();
        if (!$mc){
            return false;
        }

        $full_key = self::cache_key($key);
        $mc->incr($full_key);
        return true;
        */
    }

    /**
     * returns false if key not found
     * @param $key
     * @return array|bool|string
     */
    public function get($key)
    {
        $redis_connection_obj = $this->connection();
        if (!$redis_connection_obj) {
            return false;
        }

        $full_key = $this->cacheKey($key);
        $result = $redis_connection_obj->get($full_key);

        // TODO: null values are not processed correctly - fix
        if ($result === null) {
            return false;
        }

        $result = unserialize($result);

        return $result;
    }

    public function delete($key)
    {
        $redis_connection_obj = $this->connection();
        if (!$redis_connection_obj) {
            return false;
        }

        $full_key = $this->cacheKey($key);
        return $redis_connection_obj->del([$full_key]);
    }

    /**
     * @return null|\Predis\Client
     * @throws \Exception
     */
    public function connection()
    {
        if ($this->redis) {
            return $this->redis;
        }

        $servers = $this->servers;
        if (!count($servers)) {
            return null;
        }

        $servers_arr = [];
        foreach ($servers as $server_settings_obj) {
            // TODO: check server class
            $servers_arr[] = [
                'scheme'   => 'tcp',
                'host'     => $server_settings_obj->host,
                'port'     => $server_settings_obj->port
            ];
        }

        //$cache_engine_params_arr = CacheConfig::getCacheEngineParamsArr();
        $redis = new \Predis\Client($servers_arr, ['prefix' => $this->cache_key_prefix]);

        return $redis;
    }

    public function cacheKey($key)
    {
        //return md5($key);
        return $key;
    }

}