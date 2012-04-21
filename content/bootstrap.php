<?php

if (!defined('LIBPATH')) define('LIBPATH', __DIR__.'/../libs');

use hl\core\Router;
use l10n\Message;

Router::alias('/', 'home');
Router::alias('/index', 'home');
Router::alias('/start', 'some/thing/deep');
Router::alias('/place', 'some/thing/deep');

include_once LIBS_PATH.'/al13_debug/config/bootstrap.php';

Message::init('no_NB', LOCALES_PATH);

function __($id, $options = null, $echo = true) {
    if (is_bool($options)) {
        $echo = $options;
        $options = array();
    } elseif (!is_array($options)) {
        $options =array();
    }
    $result = Message::translate($id, $options);
    if ($echo)
        echo $result;
    else
        return $result;
}

function __n($singularId, $pluralId, $nr, $options = null, $echo = true) {
    if (is_bool($options)) {
        $echo = $options;
        $options = array();
    } elseif (!is_array($options)) {
        $options =array();
    }
    $result = (abs($nr) == 1) ?
        Message::translate($singularId, $options)
            :
        Message::translate($pluralId, $options);
    if ($echo)
        echo $result;
    else
        return $result;
}
