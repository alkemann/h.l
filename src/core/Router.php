<?php

namespace alkemann\hl\core;

class Router {

    protected static $_aliases = [];
    protected static $_routes = [];

    public static function request_to_url($requestUrl) {
        $requestUrl = is_array($requestUrl) && isset($requestUrl['url']) ? $requestUrl['url'] : '/';

        $cb = static::match($requestUrl);
        if ($cb) {
            return $cb;
        }

        $requestUrl = rtrim($requestUrl, '/');
        if (strrpos($requestUrl, '.html')) $requestUrl = substr($requestUrl, 0, -5);
        $requestUrl = '/' . $requestUrl;
        return isset(static::$_aliases[$requestUrl]) ? static::$_aliases[$requestUrl] : $requestUrl;
    }

    public static function alias($alias, $real) {
        static::$_aliases[$alias] = $real;
    }

    public static function aliases() {
        return static::$aliases;
    }

    public static function add($url, \Closure $closure) {
        static::$_routes[$url] = $closure;
    }

    public static function match($url) {
        foreach (static::$_routes as $route => $cb) {
            if ($url === $route) return $cb;
            $result = preg_match($route, $url, $matches);
            if (!$result) continue;

            $parameters = array_filter($matches, function($v) {return !is_int($v);}, 
                ARRAY_FILTER_USE_KEY);

            return new Route($url, $route, $cb, $parameters);

        }
        return false;
    }
}
