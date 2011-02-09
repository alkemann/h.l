<?php
//** semi config
define('CONTENT_DIR', 'content');
define('LAYOUT_DIR', 'layout');
define('LIB_DIR', 'lib');
//** auto defines
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(dirname(__FILE__))));
define('WEBROOT_DIR', basename(dirname(__FILE__)));
define('WWW_ROOT', dirname(__FILE__) . DS);
define('CORE_PATH', ROOT . DS . LIB_DIR . DS);
define('CONTENT_PATH', ROOT . DS . CONTENT_DIR . DS);
define('LAYOUT_PATH', CONTENT_PATH . LAYOUT_DIR . DS);

define('DEBUG', 2);

if (DEBUG) include CORE_PATH . 'debug.php';

require CORE_PATH . 'exceptions' . DS . 'exceptions.php';
require CORE_PATH . 'dispatch.php';
$Dispatch = new \core\Dispatch();
echo $Dispatch->run();
