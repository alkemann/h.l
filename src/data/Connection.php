<?php

namespace alkemann\hl\data;

class Connection {
    const TYPE_MYSQL = 'MYSQL';

    protected $_config = [];

    public function __construct($config) {
        if (is_string($config)) {
            $config = (require $config);
        }
        if (!is_array($config)) {
            throw new Exception("Config is not an array!");
        }
        $defaults = [
            'host'      => 'localhost',
            'username'  => '',
            'password'  => '',
            'port'      => '',
            'type'      => static::TYPE_MYSQL
        ];
        $this->_config = $config + $defaults;
    }

    public function __get($name) {
        if (array_key_exists($name, $this->_config)) {
            return $this->_config[$name];
        }
        return '';
    }
}
