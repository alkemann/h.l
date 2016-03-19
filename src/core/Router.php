<?php

namespace alkemann\hl\core;

class Router {

    protected static $_aliases = [];
    protected static $_routes = [];

    public static function alias($alias, $real) {
        static::$_aliases[$alias] = $real;
    }

    public static function add($url, \Closure $closure, $methods = [Request::GET]) {
        foreach ((array) $methods as $method)
            static::$_routes[$method][$url] = $closure;
    }

    public static function match($url, $method = Request::GET) {
        $url = is_array($url) && isset($url['url']) ? $url['url'] : '/';
        if (isset(static::$_routes[$method])) {
            // Look for dynamic routes
            foreach (static::$_routes[$method] as $route => $cb) {
                if ($url === $route) return $cb;
                $result = preg_match($route, $url, $matches);
                if (!$result) continue;

            $parameters = array_filter($matches, function($v) {return !is_int($v);}, 
                ARRAY_FILTER_USE_KEY);

                return new Route($url, $route, $cb, $parameters);

            }
        }
        // Not a dynamic route, assume static route
        $routeUrl = static::convertRequestUrlToPageUrl($url);
        return new Route($routeUrl, $url, function($request) {
            // unpack the url and convert to a page file path
            // set the view on the request then return a Page
            $url = $request->url();
            $parts = \explode('/', $url);
            $last = \array_slice($parts, -1, 1, true);
            unset($parts[key($last)]);
            $request->setPath($parts);
            $view = current($last);
            $request->setView($view);
            $period = strrpos($view, '.');
            if ($period) {
                $type = substr($view, $period + 1);
                if ($request->validType($type)) {
                    $request->overrideResponseType($type);
                    $view = substr($view, 0, $period);
                    $request->setView($view);
                }
            }
            return new Page($request);
        });
    }

    private static function convertRequestUrlToPageUrl($url) {
        $url = rtrim($url, '/');
        if (strrpos($url, '.html'))
            $url = substr($url, 0, -5);
        $url = '/' . $url;
        return isset(static::$_aliases[$url]) ? static::$_aliases[$url] : $url;
    }
}
