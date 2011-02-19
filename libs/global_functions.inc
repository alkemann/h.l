<?php
/**
  * Global convenience methods
  */

// Debug dump any amount of variable
function d() {
    $debug = \util\Debug::get_instance();
    $args = func_get_args();
    $trace = debug_backtrace();
    $split = true;
    if (count($args) == 1) {
        $split = false;
        $args = $args[0];
    }
    $debug->dump($args, compact('trace', 'split'));
};

// Debug dump any amount of variables and then die()
function dd() {
    $debug = \util\Debug::get_instance();
    $args = func_get_args();
    $trace = debug_backtrace();
    $split = true;
    if (count($args) == 1) {
        $split = false;
        $args = $args[0];
    }
    $debug->dump($args, compact('trace', 'split'));
    die('<div style="margin-top: 25px;font-size: 10px;color: #500;">-Debug die-</div>');
}

// Convenient wrapper for other \util\Debug methods
function dw($method, $echo = true) {
    $debug = \util\Debug::get_instance();
    $result = $debug->$method();
    if (!$echo) return $result;
    $debug->dump($result);
}
