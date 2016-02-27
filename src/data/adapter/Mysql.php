<?php

namespace alkemann\hl\data\adapter;

use alkemann\hl\data\Connection;
use Exception;
use mysqli;

class Mysql {

    protected $_connection = null;
    protected $_config = [];

    private $mysql;

    public function __construct(Connection $connection, array $config = []) {
        $this->_connection = $connection;

        $defaults = ['auto_connect' => true];
        $this->_config = $config + $defaults;

        if ($this->_config['auto_connect']) {
            $this->connect();
        }
    }

    public function connect() {
        $host = $this->_connection->host;
        $user = $this->_connection->username;
        $pass = $this->_connection->password;
        if ($database = $this->_connection->db) {
            $this->mysql = new mysqli($host, $user, $pass, $database);
        } else {
            $this->mysql = new mysqli($host, $user, $pass);
        }
    }

    public function close() {
        $this->mysql->close();
    }

    public function db($database) {
        $this->mysql->select_db($database) or die("Can't select database [$database]");
        return $this;
    }

    public function escape($value) {
        return "'" . $this->mysql->escape_string($value) . "'";
    }

    public function q($query, array $params = []) {
        $result = $this->mysql->query($query);
        return $result;
    }

    public function insert($table, array $data) {
        if (!$data) return false;
        $fields = join('`,`', array_keys($data));
        $fields = "`$fields`,`updated`,`created`";
        $values = [];
        foreach ($data as $value) {
            $values[] = $this->mysql->escape_string($value);
        }
        $values = join("','", $values);
        $values = "'$values',NOW(),NOW()";
        $query  = "INSERT INTO `$table` ($fields) VALUES ($values);";
        \alkemann\hl\util\Log::debug("Query:" . $query);
        $result = $this->mysql->query($query);
        if ($result !== true) {
            return false;
        }
        return $this->mysql->insert_id;
    }
}
