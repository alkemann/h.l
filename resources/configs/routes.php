<?php

use hl\core\Router;

Router::alias('/', 'home');
Router::alias('/index', 'home');
Router::alias('/start', 'some/thing/deep');
Router::alias('/place', 'some/thing/deep');
