<?php
/**
 * Merge view with layout files, set headers and return;
 */
namespace alkemann\hl\core;

class Render {

    protected $_request;
    protected $_data = [];

    public function __construct($requestObj) {
        $this->_request = $requestObj;
    }

    public function request() {
        return $this->_request;
    }

    public function respondWith404() {
        header("HTTP/1.0 404 Not Found");
    }

    public function respondWith400($message = 'Bad Request') {
        header("HTTP/1.0 400 $message");
    }

    public function head() {
        ob_start();
        $render = $this;
        $type = $this->_request->type();

        $headfile = LAYOUT_PATH . 'head.' . $type . '.php';
        if (file_exists($headfile))
            include $headfile;

        $neckfile = LAYOUT_PATH . 'neck.' . $type . '.php';
        if (file_exists($neckfile))
            include $neckfile;

        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }

    public function view($view) {
        $file = CONTENT_PATH . 'pages' . DS . $view . '.php';
        if (!file_exists($file)) {
            $customFile = CONTENT_PATH . 'custom' . DS . $view . '.php';
            if (!file_exists($customFile)) 
                throw new \alkemann\hl\core\exceptions\InvalidUrlException($file);
            else 
                $file = $customFile;
        }
        ob_start();
        include $file;
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }

    public function foot() {

        $type = $this->_request->type();

        $footfile = LAYOUT_PATH . 'foot.' . $type . '.php';
        if (!file_exists($footfile))
            return '';

        ob_start();
        include $footfile;
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }

    public function render($content = null) {
        $contentType = $this->_request->contentType();
        header("Content-type: $contentType");
        if (!$content) {
            $view = $this->_request->viewToRender();
            $content = $this->view($view);
        }
        $response = $this->head();
        $response .= $content;
        $response .= $this->foot();
        echo $response;
    }

    public function setData($key, $value) {
        $this->_data[$key] = $value;
    }

    public function __get($name) {
        if (isset($this->_data[$name])) {
            return $this->_data[$name];
        }
        return "$name:N/A";
    }
}
