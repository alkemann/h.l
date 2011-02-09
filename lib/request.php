<?php
/**
 *
 *
 */
namespace core;

class Request {
    
    protected $_url;
    protected $_fullUrl;
    protected $_type;
    protected $_static;
    protected $_webroot;
    protected $_path;
    protected $_view;

    public function __construct($_REQUEST) {
        $this->_url = $_REQUEST;
        $this->_type = $this->type();
    }
    
    private function type() {
        return 'html';
    }
    
}
