<?php
/**
 * Dispatch for Request and Route jobs
 */
namespace core;

require CORE_PATH . 'render.php';
include CORE_PATH . 'request.php';

class Dispatch {
    
    protected $_render;
    protected $_request;
    
    public function __construct() {
        $this->_render = new Render();
        $this->_request = new Request($_REQUEST);
    }

    public function run() {
        $response = $this->_render->head();
        $response .= $this->_render->view($this->_request->viewToRender());
        $response .= $this->_render->foot();
        return $response;
    }

}
