<?php

namespace alkemann\hl\data;

use alkemann\hl\data\Connection;
use Exception;

class Db {
    protected $_connection = null;
    protected $_adapter = null;

    protected $_classes = [
        'adapters' => [
            Connection::TYPE_MYSQL => 'alkemann\hl\data\adapter\Mysql'
        ],
        'connection' => 'alkemann\hl\data\Connection'
    ];

    public function __construct(Connection $connection, array $options = []) {
        $this->_connection = $connection;
        $adapter_class = array_key_exists('adapter', $options) ? $options['adapter'] : $this->adapterFromConnectionType($connection);
        $this->_adapter = new $adapter_class($connection);
    }

    protected function adapterFromConnectionType(Connection $connection) {
        if (!isset($this->_classes['adapters'][$connection->type])) {
            throw new Exception("Undefined connection type [{$connection->type}]");
        }
        return $this->_classes['adapters'][$connection->type];
    }

    public function adapter() {
        return $this->_adapter;
    }

    public function __call($method, $args) {
        $adapter = $this->adapter();
        return call_user_func_array([$adapter, $method], $args);
    }
}
