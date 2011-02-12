<?php
/**
 *
 *
 */
namespace core;

use \core\Router;

class Request {
    
    protected $_url;
    protected $_fullUrl;
    protected $_type = 'html';
    protected $_validTypes = array('html','json');
    protected $_static;
    protected $_webroot;
    protected $_path;
    protected $_view;

    public function __construct() {
        $this->_url = Router::request_to_url($_REQUEST);
        
        $parts = \explode('/', $this->_url);
        $last = \array_slice($parts, -1, 1, true);
        unset($parts[key($last)]);
        $this->_path = $parts;
        
        $this->_view = current($last);
        if ($p = strrpos($this->_view, '.')) {
            $type = substr($this->_view, $p + 1);
            if (in_array($type, $this->_validTypes)) {
                $this->_type = $type;
                $this->_view = substr($this->_view, 0, $p);
            }
        }        
    }
 
    public function viewToRender() {
        $ret = join(DS, $this->path()) . DS .
            $this->view() . '.' .
            $this->type();
        $ret = trim($ret, DS);
        return $ret;
    }
    
    public function path() { return $this->_path; }
    public function view() { return $this->_view; }
    public function type() { return $this->_type; }
    
    public function canon() {
        $view = $this->viewToRender();
        $view = str_replace('\\', '/', $view);
        $base = 'http://hjemmesiden.l/'; //@todo get base url;
        return $base . $view;
    }
}
