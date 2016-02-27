<?php

namespace alkemann\hl\data;

use alkemann\hl\data\Db;

class Model
{
    protected $db;
    protected $entity_class;
    protected $config = [];

    public function __construct(Db $db, array $config = [])
    {
        $defaults = [
            'entity_class' => 'alkemann\hl\data\Entity'
        ];
        $config += $defaults;
        foreach ($config as $k => $v) $this->config[$k] = $v;
        $this->entity_class = $this->config['entity_class'];
        $this->db = $db->db($this->config['db']);
    }

    public function create(array $data = [])
    {
        $entity_class = $this->entity_class;
        return new $entity_class($this, $data);
    }

    protected function db()
    {
        return $this->db;
    }

    protected function table()
    {
        return $this->config['table'];
    }

    protected function pk()
    {
        return isset($this->config['pk']) ? $this->config['pk'] : 'id';
    }

    public function fields()
    {
        return isset($this->config['fields']) ? $this->config['fields'] : [];
    }

    protected function filter_fields($data)
    {
        $return = [];
        $fields = isset($this->config['fields']) ? $this->config['fields'] : [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) $return[$field] = $data[$field];
        }
        return $return;
    }

    public function get($id)
    {
        $db     = $this->db();
        $id     = $db->escape($id);
        $pk     = $this->pk();
        $table  = $this->table();
        $result = $db->q("SELECT * FROM `$table` WHERE $pk=$id LIMIT 1;");
        if (!$result) {
            return null;
        }
        $data = $result->fetch_assoc();
        if (!$data) {
            return null;
        }
        $c = $this->entity_class;
        $obj = new $c($this, $data);
        return $obj;
    }

    public function find(array $conditions = [], array $options = [])
    {
        $db     = $this->db();
        $table  = $this->table();
        $fields = $this->fields();
        $query = "SELECT * FROM `$table`";
        if ($conditions) {
            $query .= " WHERE 1=1";
            foreach ($conditions as $field => $value) {
                if (in_array($field, $fields)) {
                    $query .= " AND `$field` = $value"; // @TODO escape
                }
            }
        }

        if (isset($options['limit'])) {
            $query .= " ORDER BY {$options['order']}";
        }

        if (isset($options['limit'])) {
            $query .= " LIMIT {$options['limit']}";
            if (isset($options['offset'])) {
                $query .= ',' . $options['offset'];
            }
        } else {
            $query .= " LIMIT 10";
        }

        $c = $this->entity_class;
        $result = $db->q($query);
        $return = [];
        while ($result && $data = $result->fetch_assoc()) {
            $return[] = new $c($this, $data);
        }
        return $return;
    }

    public function save(Entity $entity, array $data = [], array $options = [])
    {
        $data += $entity->data();

        $db = $this->db();
        $table = $this->table();

        $id = $db->insert($table, $this->filter_fields($data));
        if (!$id) return false;

        $inserted = static::get($id);

        $entity->reset();
        $entity->data($inserted->data);

        unset($inserted);

        return true;
    }
}
