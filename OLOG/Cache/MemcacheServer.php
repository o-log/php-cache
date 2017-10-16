<?php

namespace OLOG\Cache;

class MemcacheServer
{
    public $host;
    public $port;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }
}