<?php

namespace alkemann\hl\data;

use alkemann\hl\data\Connection;

function database(array $config = []) {
    $connection = new Connection($config);
    $adapter = adapter($connection, $config);
    $db_name = $config['database'];
    return $adapter->db($db_name);
}

function adapter(Connection $connection, array $config = []) {
    $adapter_class = $config['adapter']; // check?
    return new $adapter_class($connection);
}
