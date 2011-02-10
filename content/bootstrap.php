<?php

// Set up custom routes
function url_route($requestUrl) {
    $aliases = array(
        '/' => 'home',
        '/some/thing/deep' => 'start'
    );
    
    return isset($aliases['/' . $requestUrl]) ? $aliases['/' . $requestUrl] : $requestUrl;
}
