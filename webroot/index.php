<?php define('FIRST_APP_CONSTANT', 'FIRST_APP_CONSTANT');
//** semi config
define('CONTENT_DIR', 'content');
define('LAYOUT_DIR', 'layout');
define('LIB_DIR', 'libs');
define('RESOURCE_DIR', 'resources');
define('CORE_DIR', 'core');
//** auto defines
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(dirname(__FILE__))));
define('WEBROOT_DIR', basename(dirname(__FILE__)));
define('WWW_ROOT', dirname(__FILE__) . DS);
define('CONTENT_PATH', ROOT . DS . CONTENT_DIR . DS);
define('LAYOUT_PATH', CONTENT_PATH . LAYOUT_DIR . DS);
define('LIBS_PATH', ROOT . DS . LIB_DIR . DS);
define('CORE_PATH', LIBS_PATH . CORE_DIR . DS);
define('LOGS_PATH', ROOT . DS . RESOURCE_DIR . DS . 'logs' . DS);
define('LOCALES_PATH', ROOT . DS . RESOURCE_DIR . DS . 'locales' . DS);

define('DEBUG', 1);

define('CLASS_DIR', LIBS_PATH); // USED BY SPL_AUTOLOADER
set_include_path(get_include_path().PATH_SEPARATOR.CLASS_DIR);
spl_autoload_extensions('.php');
spl_autoload_register();

$Dispatch = new hl\core\Dispatch();
require CONTENT_PATH . 'bootstrap.php';

try {
    $Dispatch->run();
} catch (Exception $e) {
    $errorHandler = new hl\core\exceptions\ErrorHandler($e);
    $errorHandler->dispatch($Dispatch);
    $errorHandler->deal();
}
