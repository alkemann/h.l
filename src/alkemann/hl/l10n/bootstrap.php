<?php

use l10n\Message;

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
