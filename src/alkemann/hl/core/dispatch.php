<?php
/**
 * Dispatch for Request and Route jobs
 */
namespace hl\core;

class Dispatch {

    public $_render;
    public $_requestObj;

    public function run() {
        $this->_requestObj = new Request();
        $this->_render = new Render($this->_requestObj);
        $this->_render->render();
    }

    public function raw($content) {
    	if (!$this->_render) {
	        $this->_render = new Render($this->_requestObj);
	    }
        $this->_render->render($content);
    }
}
