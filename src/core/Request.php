<?php
/**
 *
 *
 */
namespace alkemann\hl\core;

use alkemann\hl\core\Router;

class Request {

    const GET = 'GET';
    const PATCH = 'PATCH';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    protected $_url;
    protected $_fullUrl;
    protected $_type = 'html';
    protected $_validTypes = array('html','json', 'xml');
    protected $_contentTypes = array('html' => 'text/html', 'json' => 'application/json', 'xml' => 'application/xml');
    protected $_static;
    protected $_webroot;
    protected $_path;
    protected $_view;
    protected $_method;
    protected $_route;

    public function __construct() {
        $this->_method = $_SERVER['REQUEST_METHOD'];
        $this->setResponseTypeFromHeaders();

        $route = Router::request_to_url($_REQUEST, $_SERVER['REQUEST_METHOD']);

        if ($route instanceof Route) {
            $this->_url = $_REQUEST['url'];
            $this->_route = $route;
        } else {
            $this->_url = $route;
            $this->setPageFromUrl();
        }
    }

    private function setPageFromUrl() {
        $parts = \explode('/', $this->_url);
        $last = \array_slice($parts, -1, 1, true);
        unset($parts[key($last)]);
        $this->_path = $parts;
        $this->_view = current($last);

        $period = strrpos($this->_view, '.');
        if ($period) {
            $type = substr($this->_view, $period + 1);
            if (in_array($type, $this->_validTypes)) {
                $this->_type = $type;
                $this->_view = substr($this->_view, 0, $period);
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

    public function getPostBody() {
        return file_get_contents('php://input');
    }

    public function method() { return $this->_method; }
    public function path() { return $this->_path; }
    public function view() { return $this->_view; }
    public function type() { return $this->_type; }
    public function validType() { return in_array($this->type(), $this->_validTypes); }
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
        if ($this->_route) {
            $cb = $this->_route->callback;
            $this->_parameters = $this->_route->parameters;
            return call_user_func_array($cb, [$this]);
        }
        return new Page($this);
    }
}
