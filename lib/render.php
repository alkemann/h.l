<?php
/**
 * Merge view with layout files, set headers and return;
 */
namespace core;

class Render {

    public function head() {
        include LAYOUT_PATH . 'head.html.php';
        include LAYOUT_PATH . 'neck.html.php';
    }

    public function view() {
        //include CONTENT_PATH . 'pages' . DS . 'start.html.php';
        dd($_SERVER);
    }

    public function foot() {
        include LAYOUT_PATH . 'foot.html.php';
    }

}
