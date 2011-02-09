<?php
/**
 * Include all required lib files needed for any operation
 */
 
require CORE_PATH . 'dispatch.php';

require CORE_PATH . 'render.php';

$Dispatch = new \core\Dispatch();
$Dispatch->run();
