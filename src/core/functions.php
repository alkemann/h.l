<?php

namespace alkemann\hl\core;

use alkemann\hl\core\exceptions\InvalidUrlException;

function handleError(\Exception $exception, Request $request) {
    if ($exception instanceof InvalidUrlException) {
        header("HTTP/1.0 404 Not Found");
        $page = new Page($request);
        $page->setRequestAs404();
        $page->setData('missing', $exception->getMessage());
        $page->render();
    } elseif (DEBUG === 0) {
        $file = LOGS_PATH . 'error.' . time() . '.log';
        $fileHandler = fopen($file, 'c');
        $exceptionMessage = strip_tags($exception->__toString());
        fwrite($fileHandler, $exceptionMessage);
        fwrite($fileHandler, "\n\n");
        fwrite($fileHandler, print_r($request, true));
        fclose($fileHandler);
        header("HTTP/1.0 500 Interal Server Error");

        $errorPage = CONTENT_PATH . 'custom' . DS . 'error500.html.php';
        if (file_exists($errorPage)) {
            include $errorPage;
        }
    } else {
        (new Page($request))->render((string) $exception);
    }
}