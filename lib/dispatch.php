<?php
/**
 * Dispatch for Request and Route jobs
 */
namespace core;

class Dispatch {
    
    protected $_render;
    
    public function __construct() {
        $this->_render = new Render();
    }

    public function run() {
        $this->_render->view();
    }

}
