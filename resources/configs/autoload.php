<?php

define('CLASS_DIR', LIBS_PATH); // USED BY SPL_AUTOLOADER
set_include_path(get_include_path() . PATH_SEPARATOR . CLASS_DIR);
spl_autoload_extensions('.php');
spl_autoload_register();
