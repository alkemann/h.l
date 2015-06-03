<?php

namespace alkemann\hl\core;

use alkemann\hl\core\exceptions\InvalidUrlException;

class ErrorHandler {

    protected $_dispatch;
    protected $_exception;

    public function __construct(\Exception $e) {
        $this->_exception = $e;
    }

    public function dispatch($d) {
        $this->_dispatch = $d;
    }

    public function deal() {
        if ($this->_exception instanceof InvalidUrlException) return $this->render404();
        if (DEBUG === 0) return $this->production();
        $this->_dispatch->raw((string) $this->_exception);
    }

    protected function render404() {
        header("HTTP/1.0 404 Not Found");
        $request = $this->_dispatch->_requestObj;
        $render = $this->_dispatch->_render;
        $request->setRequestAs404();
        $render->setData('missing', $this->_exception->getMessage());
        $render->render();
    }

    protected function production() {
        $file = LOGS_PATH . 'error.' . time() . '.log';
        $fileHandler = fopen($file, 'c');
        $exceptionMessage = strip_tags($this->_exception->__toString());
        fwrite($fileHandler, $exceptionMessage);
        fwrite($fileHandler, "\n\n");
        fwrite($fileHandler, print_r($this->_dispatch->getRequest(), true));
        fclose($fileHandler);
        header("HTTP/1.0 500 Interal Server Error");

        $errorPage = CONTENT_PATH . 'custom' . DS . 'error500.html.php';
        if (file_exists($errorPage)) {
            include $errorPage;
        }
        exit;
    }

}
