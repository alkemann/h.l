<?php

namespace alkemann\hl\core;

class Response {

    protected $_request;
    protected $_content;
    protected $_format;

    public function __construct($requestObj, $content = null, $format = 'json') {
        $this->_request = $requestObj;
        $this->_content = $content;
        $this->_format  = $format;
    }

    public function request() {
        return $this->_request;
    }

    public function respondWith404() {
        header("HTTP/1.0 404 Not Found");
    }

    public function respondWith400($message = 'Bad Request') {
        header("HTTP/1.0 400 $message");
    }

    public function setResponse($content, $format = 'json') {
        $this->_content = $content;
    }

    public function render() {
        $contentType = $this->_request->contentType();
        header("Content-type: $contentType");
        echo $this->_content;
    }
}
