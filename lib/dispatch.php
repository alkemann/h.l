<?php
/**
 * Dispatch for Request and Route jobs
 */
namespace core;

include CORE_PATH . 'request.php';

class Dispatch {
    
    protected $_render;
    protected $_request;
    
    public function __construct() {
        $this->_render = new Render();
        $this->_request = new Request($_REQUEST);
    }

    public function run() {
        $this->_render->view();
    }

}
