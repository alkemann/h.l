<?php

namespace alkemann\hl\core;

use alkemann\hl\core\Request;
use alkemann\hl\core\Render;

function handleError(\Exception $exception, Request $request) {
        if ($exception instanceof alkemann\hl\core\exceptions\InvalidUrlException) {
            header("HTTP/1.0 404 Not Found");
            $render = $dispatch->_render;
            $request->setRequestAs404();
            $render->setData('missing', $exception->getMessage());
            $render->render();
        } elseif (DEBUG === 0) {
            $file = LOGS_PATH . 'error.' . time() . '.log';
            $fileHandler = fopen($file, 'c');
            $exceptionMessage = strip_tags($exception->__toString());
            fwrite($fileHandler, $exceptionMessage);
            fwrite($fileHandler, "\n\n");
            fwrite($fileHandler, print_r($dispatch->getRequest(), true));
            fclose($fileHandler);
            header("HTTP/1.0 500 Interal Server Error");

            $errorPage = CONTENT_PATH . 'custom' . DS . 'error500.html.php';
            if (file_exists($errorPage)) {
                include $errorPage;
            }
        } else {
            $render = new Render($request);
            $render->render((string) $exception);
        }

}