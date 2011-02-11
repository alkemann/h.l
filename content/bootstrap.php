<?php

// Set up custom routes
function url_route($requestUrl) {

    $requestUrl = is_array($requestUrl) && isset($requestUrl['url']) ? $requestUrl['url'] : '/';
    $requestUrl = rtrim($requestUrl, '/');
    $requestUrl = '/' . $requestUrl;
    $aliases = array(
        '/' => 'home',
        '/start' => 'start/something/deep'
    );
    
    return isset($aliases[$requestUrl]) ? $aliases[$requestUrl] : $requestUrl;
}
