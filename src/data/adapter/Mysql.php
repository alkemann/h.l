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
        $result = $this->mysql->select_db($database);
        if (!$result) {
            die($this->mysql->error . " \n Can't select database [$database]");
        }
        return $this;
    }

    public function escape($value) {
        return "'" . $this->mysql->escape_string($value) . "'";
    }

    public function q($query, array $params = []) {
        $result = $this->mysql->query($query);
        $last_error = $this->mysql->error;
        if ($last_error) {
            \alkemann\hl\util\Log::error("MYSQL: " . $last_error);
        }
        return $result;
    }

    public function update($table, array $conditions,  array $data) {
        if (!$conditions || !$data) return false;

        $values = [];
        foreach ($data as $field => $value) {
            $field = $this->mysql->escape_string($field);
            $value = $this->mysql->escape_string($value);
            $values[] = "`$field` = '$value'";
        }
        $values[] = "`updated` = NOW()";
        $where = [];
        foreach ($conditions as $field => $value) {
            $field = $this->mysql->escape_string($field);
            $value = $this->mysql->escape_string($value);
            $where[] = "`$field` = '$value'";
        }
        if (!$where) {
            \alkemann\hl\util\Log::error("No where conditions for update!");
            return false;
        }
        $query = "UPDATE `$table` SET ";
        $query .= join(', ', $values);
        $query .= " WHERE " . join(' AND ', $where);
        \alkemann\hl\util\Log::debug("Query: " . $query);
        $result = $this->mysql->query($query);
        $last_error = $this->mysql->error;
        if ($last_error) {
            \alkemann\hl\util\Log::error("MYSQL: " . $last_error);
        }
        if ($result !== true) {
            return false;
        }
        return $this->mysql->affected_rows;
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
        \alkemann\hl\util\Log::debug("Query: " . $query);
        $result = $this->mysql->query($query);
        $last_error = $this->mysql->error;
        if ($last_error) {
            \alkemann\hl\util\Log::error("MYSQL: " . $last_error);
        }
        if ($result !== true) {
            return false;
        }
        return $this->mysql->insert_id;
    }

    public function delete($table, array $conditions) {
        if (!$conditions) return false;
        $where = [];
        foreach ($conditions as $field => $value) {
            $field = $this->mysql->escape_string($field);
            $value = $this->mysql->escape_string($value);
            $where[] = "`$field` = '$value'";
        }
        $query = "DELETE FROM `$table` WHERE " . join(' AND ', $where);
        \alkemann\hl\util\Log::debug("Query: " . $query);
        $result = $this->mysql->query($query);
        $last_error = $this->mysql->error;
        if ($last_error) {
            \alkemann\hl\util\Log::error("MYSQL: " . $last_error);
        }
        if ($result !== true) {
            return false;
        }
        return $this->mysql->insert_id;
    }
}
