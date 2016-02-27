<?php

namespace alkemann\hl\data;

class Entity implements \JsonSerializable
{
    protected $model;
    protected $data = [];

    public function __construct(Model $model, array $data = null)
    {
        $this->model = $model;
        if ($data && is_array($data)) {
            $this->data = $data;
        }
    }

    public function exists()
    {
        $pk = $this->model->pk();
        return $this->$pk;
    }

    public function data(array $data = null)
    {
        if (is_null($data)) {
            return $this->data;
        }
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
        return $this->data;
    }

    public function reset()
    {
        $this->data = [];
    }

    public function __get($name)
    {
        return array_key_exists($name, $this->data) ? $this->data[$name] : null;
    }

    public function jsonSerialize()
    {
        return $this->data;
    }

    public function __call($method, array $args = [])
    {
        if (method_exists($this->model, $method)) {
            array_unshift($args, $this);
            return call_user_func_array([$this->model, $method], $args);
        }
    }
}
