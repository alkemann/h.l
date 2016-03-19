<?php

namespace alkemann\hl\core;

class Route {

    public $url;
    public $pattern;
    public $callback;
    public $parameters;

    public function __construct($url, $pattern, \Closure $cb, array $parameters = []) {
        $this->url = $url;
        $this->pattern = $pattern;
        $this->callback = $cb;
        $this->parameters = $parameters;
    }
}
