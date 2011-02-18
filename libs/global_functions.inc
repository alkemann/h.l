<?php
/**
  * Global convenience methods
  */

// Debug dump any amount of variable
function d() {
    $debug = \util\Debug::get_instance();
    $args = func_get_args();
    $trace = debug_backtrace();
    foreach ($args as $var) $debug->dump($var, compact('trace'));
};

// Debug dump any amount of variables and then die()
function dd() {
    $debug = \util\Debug::get_instance();
    $args = func_get_args();
    $trace = debug_backtrace();
    foreach ($args as $var) $debug->dump($var, compact('trace'));
    die('<div style="margin-top: 25px;font-size: 10px;color: #500;">-Debug die-</div>');
}

// Convenient wrapper for other \util\Debug methods
function dw($method, $echo = true) {
    $debug = \util\Debug::get_instance();
    $result = $debug->$method();
    if (!$echo) return $result;
    $debug->dump($result);
}

