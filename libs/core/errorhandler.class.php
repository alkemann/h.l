<?php

namespace core;

class ErrorHandler {

    protected $dispatch;
    protected $exception;

    public function __construct(\Exception $e) {
        $this->exception = $e;
    }

    public function setDispatch($d) {
        $this->dispatch = $d;
    }

    public function deal() {
        if (DEBUG === 0) return $this->production();
        throw $this->exception;
    }

    public function production() {
        if ($this->exception instanceof \core\exceptions\InvalidUrlException) {
            header("HTTP/1.0 404 Not Found");
            echo $this->dispatch->getRender()->view('error404.html.php');
            exit;
        }
        $file = LOGS_PATH . 'error.' . time() . '.log';
        $fileHandler = fopen($file, 'c');
        fwrite($fileHandler, $this->exception->__toString());
        fwrite($fileHandler, "\n\n");
        fwrite($fileHandler, print_r($this->dispatch->getRequest(), true));
        fclose($fileHandler);
        header("HTTP/1.0 500 Interal Server Error");
        
        $errorPage = CONTENT_PATH . 'pages' . DS . 'error500.html.php';
        if (file_exists($errorPage)) {
            include $errorPage;
        }
        exit;
    }

}
