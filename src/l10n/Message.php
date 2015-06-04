<?php

namespace alkemann\hl\l10n;

class Message {

    private static $__strings = array();
    private static $__path = null;
    
    public static function init($locale = 'no_NB', $path = null) {
        if ($path == null) $path = __DIR__;
        static::$__path = $path;
    }
    
    public static function locale($locale = 'no_NB') {
        $file = static::$__path.$locale.'.php';;
        if (file_exists($file)) {
            include $file;
            static::$__strings = $translations;
        }
    }

    public static function translate($id, array $options = array()) {
        $defaults = array(
            'count' => 1,
            'default' => $id,
        );
        $options += $defaults;

        $result = static::_translated($id, abs($options['count']));

        if ($result || $options['default']) {
            return String::insert($result ?: $options['default'], $options);
        }
    }

    protected static function _translated($id, $count) {
        $trans = array_key_exists($id, static::$__strings) ? static::$__strings[$id] : null;
        return $trans;
    }

}
