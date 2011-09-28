<?php

if (!defined('LIBPATH')) define('LIBPATH', __DIR__.'/../libs');

use hl\core\Router;

Router::alias('/', 'home');
Router::alias('/index', 'home');
Router::alias('/start', 'some/thing/deep');
Router::alias('/place', 'some/thing/deep');

include_once LIBS_PATH.'/al13_debug/config/bootstrap.php';

