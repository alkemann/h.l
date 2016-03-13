<?php

namespace alkemann\hl\data;

class Model
{
    protected $db;
    protected $entity_class;
    protected $config = [];

    public function __construct(array $config = [])
    {
        $defaults = [
            'entity_class' => 'alkemann\hl\data\Entity',
            'connection' => false,
        ];
        $config += $defaults;
        foreach ($config as $k => $v) $this->config[$k] = $v;
        $this->entity_class = $this->config['entity_class'];
        if (isset($config['connection']) && $config['connection']) {
            $this->db = database($config['connection']);
        }
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

    public function pk()
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
        $result = $this->db()->find($this->table(), [$this->pk() => $id], ['limit' => 1]);
        if (!$result) {
            return null;
        }
        $c = $this->entity_class;
        $obj = new $c($this, $result[0]);
        return $obj;
    }

    public function find(array $conditions = [], array $options = [])
    {
        $db     = $this->db();
        $table  = $this->table();
        $result = $db->find($table, $conditions, $options);
        $c = $this->entity_class;
        $return = [];
        foreach ($result as $data) {
            $return[] = new $c($this, $data);
        }
        return $return;
    }

    public function save(Entity $entity, array $data = [], array $options = [])
    {
        $db = $this->db();
        $table = $this->table();

        if ($entity->exists()) {
            // @TODO entity updated fields?
            $pk = $this->pk();
            $id = $entity->$pk;
            $conditions = [$pk => $id];
            $rows = $db->update($table, $conditions, $data);
        } else {
            $data += $entity->data();
            $id = $db->insert($table, $this->filter_fields($data));
            if (!$id) return false;

        }
        $inserted = static::get($id);
        $entity->reset();
        $entity->data($inserted->data());
        unset($inserted);

        return true;
    }

    public function delete(Entity $entity, array $options = [])
    {
        $db = $this->db();
        $table = $this->table();
        $pk = $this->pk();
        $id = $entity->$pk;
        $db->delete($table, [$pk => $id]);
    }
}
