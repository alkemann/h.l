<?php

namespace alkemann\hl\core;

use alkemann\hl\core\Router;

class Request {

    const GET = 'GET';
    const PATCH = 'PATCH';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    protected $_url;
    protected $_path;
    protected $_view;
    protected $_method;
    protected $_route;
    protected $_type = 'html';
    protected $_validTypes = ['html','json', 'xml'];
    protected $_contentTypes = [
        'html' => 'text/html',
        'json' => 'application/json',
        'xml' => 'application/xml'
    ];

    public function __construct() {
        $this->setResponseTypeFromHeaders();
        $this->_method  = $_SERVER['REQUEST_METHOD'];
        $route          = Router::match($_REQUEST, $_SERVER['REQUEST_METHOD']);
        $this->_url     = $route->url;
        $this->_route   = $route;
    }

    public function getPostBody() { return file_get_contents('php://input'); }
    public function route() { return $this->_route; }
    public function url() { return $this->_url; }
    public function method() { return $this->_method; }
    public function path() { return $this->_path; }
    public function setPath($path) { $this->_path = $path; }
    public function view() { return $this->_view; }
    public function setView($view) { $this->_view = $view; }
    public function type() { return $this->_type; }

    public function validType($type = null) {
        if (is_null($type)) $type= $this->type();
        return in_array($type, $this->_validTypes);
    }

    public function contentType() {
        if ($this->validType()) {
            return $this->_contentTypes[$this->type()];
        } else {
            return "text/html";
        }
    }

    public function param($name) {
        if (isset($this->_parameters[$name]))
            return $this->_parameters[$name];
        if (isset($_GET[$name]))
            return $_GET[$name];
        if (isset($_POST[$name]))
            return $_POST[$name];

        return null;
    }

    public function setResponseTypeFromHeaders() {
        if ($_SERVER['HTTP_ACCEPT'] === '*/*') return;
        if (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            // TODO add support for more type?
            $this->_type = 'json';
        }
    }

    public function canon() {
        $view = $this->viewToRender();
        $view = str_replace('\\', '/', $view);
        $base = 'http://hjemmesiden.l/'; //@todo get base url;
        return $base . $view;
    }

    public function viewToRender() {
        $ret = join(DS, $this->path()) . DS .
            $this->view() . '.' .
            $this->type();
        $ret = trim($ret, DS);
        return $ret;
    }

    public function setRequestAs404() {
        $this->_view = 'error404';
        $this->_path = [];
        if (!in_array($this->_type, ['html', 'json'])) {
            $this->_type = 'html';
        }
    }

    public function overrideResponseType($format) {
        $this->_type = $format;
    }

    public function response() {
        $cb = $this->_route->callback;
        $this->_parameters = $this->_route->parameters;
        return call_user_func_array($cb, [$this]);
    }

    // TODO add support for reverse route match
    public function redirect($url) {
        header( "Location: " . $url);
        exit;
    }

    public function session($name = null) {
        // TODO add support for dot notation
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        if ($name && isset($_SESSION[$name]))
            return $_SESSION[$name];
    }
}
