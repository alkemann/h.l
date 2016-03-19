<?php
/**
 * Merge view with layout files, set headers and return;
 */
namespace alkemann\hl\core;

use alkemann\hl\core\interfaces\IResponse;

class Page implements IResponse {

    protected $_data = [];
    protected $_request;
    protected $_url;
    protected $_path;
    protected $_view;
    protected $_type = 'html';
    protected $_validTypes = ['html','json', 'xml'];
    protected $_contentTypes = [
        'html' => 'text/html',
        'json' => 'application/json',
        'xml' => 'application/xml'
    ];

    public function __construct(Request $request) {
        $this->_request = $request;
        $this->_type    = $request->type();
        $this->_url     = $request->url();
        $this->setViewAndPathFromUrl();
    }

    private function setViewAndPathFromUrl() {
        $parts = \explode('/', $this->_url);
        $last = \array_slice($parts, -1, 1, true);
        unset($parts[key($last)]);
        $this->_path = $parts;
        $this->_view = current($last);
        $period = strrpos($this->_view , '.');
        if ($period) {
            $type = substr($this->_view , $period + 1);
            if ($request->validType($type)) {
                $this->_type = $type;
                $this->_view = substr($this->_view , 0, $period);
            }
        }
    }

    public function request() {
        return $this->_request;
    }

    public function head() {
        ob_start();
        $render = $this;
        $type = $this->_type;

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

    public function setRequestAs404() {
        $this->_view = 'error404';
        $this->_path = [];
        if (!in_array($this->_type, ['html', 'json'])) {
            $this->_type = 'html';
        }
    }

    public function foot() {

        $type = $this->_type;

        $footfile = LAYOUT_PATH . 'foot.' . $type . '.php';
        if (!file_exists($footfile))
            return '';

        ob_start();
        include $footfile;
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
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

    public function render($content = null) {
        $contentType = $this->contentType();
        header("Content-type: $contentType");
        if (!$content) {
            $view = $this->viewToRender();
            $content = $this->view($view);
        }
        $response = $this->head();
        $response .= $content;
        $response .= $this->foot();
        echo $response;
    }

    private function viewToRender() {
        $ret = join(DS, $this->_path) . DS .
            $this->_view . '.' .
            $this->_type;
        $ret = trim($ret, DS);
        return $ret;
    }

    private function contentType() {
        $format = $this->_type;
        if (in_array($format, $this->_validTypes)) {
            return $this->_contentTypes[$format];
        }
        return "text/html";
    }

    public function setResponseFormat($type) {
        if (in_array($type, $this->_validTypes))
            $this->_type = $type;
    }
}
