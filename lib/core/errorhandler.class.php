<?php

namespace core;

class ErrorHandler {

    protected $exception;
    
    public function __construct(\Exception $e) {
        if ($e instanceof \core\exceptions\InvalidUrlException && DEBUG === 0) {
            header("HTTP/1.0 404 Not Found");
            die();
        }
        throw $e;
    }
}