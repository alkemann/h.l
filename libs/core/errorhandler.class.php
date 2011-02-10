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
        // create instance of a logger and log exception

        if ($this->exception instanceof \core\exceptions\InvalidUrlException) {
            header("HTTP/1.0 404 Not Found");
            echo $this->dispatch->getRender()->view('error404.html.php');
            exit;
        }
        throw $this->exception;
    }

}
