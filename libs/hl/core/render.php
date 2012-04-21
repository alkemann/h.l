<?php
/**
 * Merge view with layout files, set headers and return;
 */
namespace hl\core;

class Render {

    protected $_request;

    public function __construct($requestObj) {
        $this->_request = $requestObj;
    }
    
    public function request() {
        return $this->_request;
    }

    public function head() {
        ob_start();
        $render = $this;
        include LAYOUT_PATH . 'head.html.php';
        include LAYOUT_PATH . 'neck.html.php';
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }

    public function view($view) {
        $file = CONTENT_PATH . 'pages' . DS . $view . '.php';
        if (!file_exists($file)) {
            $customFile = CONTENT_PATH . 'custom' . DS . $view . '.php';
            if (!file_exists($customFile)) 
                throw new \hl\core\exceptions\InvalidUrlException($file);
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
        ob_start();
        include LAYOUT_PATH . 'foot.html.php';
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }

    public function render($content = null) {
        if (!$content) {
            $view = $this->_request->viewToRender();
            $content = $this->view($view);
        }
        $response = $this->head();
        $response .= $content;
        $response .= $this->foot();
        echo $response;
    }

}
