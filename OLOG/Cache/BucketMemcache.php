<?php

namespace OLOG\Cache;

class BucketMemcache implements BucketInterface
{
    public $cache_key_prefix;
    public $servers = [];

    public function __construct($servers, $cache_key_prefix = ""){
        $this->servers = $servers;
        $this->cache_key_prefix = $cache_key_prefix;
    }

    public function set($key, $value, $exp)
    {
        if ($exp == -1) {
            $exp = 60;
        }

        if ($exp > 0) {
            if ($exp > 2592000) { // не добавляем тайм для мелких значений, чтобы не добавлять сложностей с разными часами на серверах и т.п.
                $exp += time();
            }
        } else {
            $exp = 0;
        }

        if($exp == 0) {
            return true;
        }

        $mc = $this->connection(); // do not check result - already checked
        if (!$mc){
            return false;
        }

        $full_key = $this->dmemcache_key($key);

        $mcs_result = $mc->set($full_key, $value, MEMCACHE_COMPRESSED, $exp);

        if (!$mcs_result) {
            return FALSE;
        }

        return TRUE;
    }

    public function increment($key)
    {
        $mc = $this->connection();
        if (!$mc){
            return false;
        }
        
        $full_key = $this->dmemcache_key($key);
        if (!$mc->increment($full_key)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function get($key)
    {
        $mc = $this->connection();
        if (!$mc){
            return false;
        }

        $full_key = $this->dmemcache_key($key);
        $result = $mc->get($full_key);

        return $result;
    }

    public function delete($key)
    {
        $mc = $this->connection();
        if (!$mc){
            return false;
        }

        $full_key = $this->dmemcache_key($key);
        return $mc->delete($full_key);
    }

    /**
     * May return null if not configured - all clients must support this and do something default (get() must return
     * false, etc.)
     * @return \Memcache|null
     * @throws \Exception
     */
    public function connection()
    {
        static $memcache = NULL;

        if (isset($memcache)) {
            return $memcache;
        }

        // no servers is not an error - just no servers, thus no caching in this bucket
        if (!count($this->servers)){
            return null;
        }

        // Memcached php extension not supported - slower, rare, extra features not needed
        $memcache = new \Memcache;

        /** @var MemcacheServer $server */
        foreach ($this->servers as $server) {
            if (!$memcache->addServer($server->host, $server->port)){
                throw new \Exception('Connect failed');
            }
            $memcache->setCompressThreshold(5000, 0.2);
        }

        return $memcache;
    }

    public function dmemcache_key($key)
    {
        $prefix = $this->cache_key_prefix;

        // empty prefix may conflict with something?
        if ($prefix == ''){
            $prefix = 'default';
        }

        $full_key = $prefix . '-' . $key;

        return md5($full_key);
    }

} 