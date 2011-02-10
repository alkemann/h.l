<?php
/**
 * Dispatch for Request and Route jobs
 */
namespace core;

class Dispatch {
    
    protected $_render;
    protected $_requestObj;
    
    public function __construct() {
        $this->_render = new Render();
        $this->_requestObj = new Request($_REQUEST);
    }

    public function run() {
        $response = $this->_render->head();
        $response .= $this->_render->view($this->_request->viewToRender());
        $response .= $this->_render->foot();
        return $response;
    }

}
