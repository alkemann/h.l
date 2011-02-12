<?php

namespace core;

class Router {

    protected static $_aliases = array();
    protected static $_routes = array();

    public static function request_to_url($requestUrl) {
        $requestUrl = is_array($requestUrl) && isset($requestUrl['url']) ? $requestUrl['url'] : '/';
        $requestUrl = rtrim($requestUrl, '/');
        $requestUrl = '/' . $requestUrl;
        return isset(static::$_aliases[$requestUrl]) ? static::$_aliases[$requestUrl] : $requestUrl;
    }
    
    public static function alias($alias, $real) {
        static::$_aliases[$alias] = $real;
    }
    
    public static function aliases() {
        return static::$aliases;
    }

}
