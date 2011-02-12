<?php
/**
 * Dispatch for Request and Route jobs
 */
namespace core;

class Dispatch {
    
    protected $_render;
    protected $_requestObj;

    public function render() {
        return $this->_render;
    }

    public function request() {
        return $this->_requestObj;
    }

    public function run() {
        $this->_requestObj = new Request();
        $this->_render = new Render($this->_requestObj);
        return $this->_render->render();
    }

}
