<?php

namespace alkemann\hl\core;

use alkemann\hl\core\interfaces\IResponse;

class Error implements IResponse {

    protected $_error;
    protected $_message;

    public function __construct($errorCode, $message = null) {
        $this->_error = $errorCode;
        $this->_message = $message;
    }

    public function render() {
        switch ($this->_error) {
            case 404:
                header("HTTP/1.0 404 Not Found");
                break;
            case 400:
                header("HTTP/1.0 400 {$this->_message}");
                break;
            default:
                header("HTTP/1.0 {$this->_error} Bad request");
                break;
        }
    }
}
