<?php
/**
 * Merge view with layout files, set headers and return;
 */
namespace core;

class Render {

    public function head() {
        ob_start();
        include LAYOUT_PATH . 'head.html.php';
        include LAYOUT_PATH . 'neck.html.php';
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }

    public function view($view) {
        $file = CONTENT_PATH . 'pages' . DS . $view;
        if (!file_exists($file)) {
            throw new \core\exceptions\InvalidUrlException($file);
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

}
