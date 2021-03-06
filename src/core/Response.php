<?php

namespace alkemann\hl\core;

use alkemann\hl\core\interfaces\IResponse;

class Response implements IResponse {

    protected $_request;
    protected $_content;
    protected $_format;

    protected $_validTypes = ['html','json', 'xml'];
    protected $_contentTypes = ['html' => 'text/html', 'json' => 'application/json', 'xml' => 'application/xml'];

    public function __construct($content = null, $format = 'json') {
        $this->_format  = $format;
        $this->setContent($content);
    }

    public function render() {
        $contentType = $this->contentType($this->_format);
        header("Content-type: $contentType");
        echo $this->_content;
    }

    private function setContent($content) {
        switch ($this->_format) {
            case 'json':
                $this->_content = json_encode($content);
                break;
            // TODO XML
            default:
                $this->_content = $content;
                break;
        }
    }

    private function contentType($format) {
        if (in_array($format, $this->_validTypes)) {
            return $this->_contentTypes[$format];
        }
        return "text/html";
    }
}
