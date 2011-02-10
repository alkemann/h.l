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

define('CLASS_DIR', CORE_PATH);
set_include_path(get_include_path().PATH_SEPARATOR.CLASS_DIR);
spl_autoload_extensions('.class.php');
spl_autoload_register();


try {
$Dispatch = new \core\Dispatch();
echo $Dispatch->run();
} catch (Exception $e) { $errorHandler = new \core\ErrorHandler($e); $errorHandler->deal(); }
