<?php

namespace OLOG\Cache;

interface BucketInterface
{
    public function set($key, $value, $exp);

    public function increment($key);

    /**
     * Must return false if key not found in cache.
     */
    public function get($key);

    public function delete($key);
}