<?php

namespace alkemann\hl\data;

function database($config = []) {
    if (is_string($config)) {
        $config = (require $config);
    }

    $defaults = [
        'host'          => 'localhost',
        'username'      => '',
        'password'      => '',
        'port'          => '',
        'type'          => 'mysql',
        'adapter_class' => 'alkemann\hl\data\adapter\Mysql',
    ];
    $config += $defaults;

    $adapter_class = $config['adapter_class'];
    $adapter = new $adapter_class($config);
    return $adapter->db($config['database']);
}
