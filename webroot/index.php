<?php
//** semi config
define('CONTENT_DIR', 'content');
define('LAYOUT_DIR', 'layout');
//** auto defines
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(dirname(__FILE__))));
define('WEBROOT_DIR', basename(dirname(__FILE__)));
define('WWW_ROOT', dirname(__FILE__) . DS);
define('CORE_PATH', ROOT . DS);
define('CONTENT_PATH', ROOT . DS . CONTENT_DIR . DS);
define('LAYOUT_PATH', CONTENT_PATH . LAYOUT_DIR . DS);


// include CORE_PATH . DS . 'bootstrap.php';


include LAYOUT_PATH . 'head.html.php';
include LAYOUT_PATH . 'neck.html.php';

// @todo
include CONTENT_PATH . 'pages' . DS . 'start.html.php';

include LAYOUT_PATH . 'foot.html.php';

// phpinfo();
