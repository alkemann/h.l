<?php

namespace alkemann\hl\util;

class Log
{
    public static $handlers = [
        'file' => ['alkemann\hl\util\Log', 'file']
    ];

    public static function __callStatic($method, array $args = [])
    {
        array_unshift($args, $method);
        call_user_func_array(['alkemann\hl\util\Log', 'log'], $args);
    }

    public static function log($level, $message, array $context = [])
    {
        foreach (static::$handlers as $handler => $callable) {
            $callable($level, $message, $context);
        }
    }

    protected static function file($level, $message, array $context = [])
    {
        $file = LOGS_PATH . 'app.log';
        $fileHandler = fopen($file, 'a');
        $string = date('Y-m-d H:i:s') . " " . strtoupper($level) . " " . $message . PHP_EOL;
        fwrite($fileHandler, $string);
        fclose($fileHandler);
    }
}
