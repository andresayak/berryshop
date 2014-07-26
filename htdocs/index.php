<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(E_ALL);
date_default_timezone_set('UTC');
define('REQUEST_MICROTIME', microtime(true));
define('INDEX_PATH', __FILE__);

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
set_error_handler('exceptions_error_handler');

// Setup error handler
function exceptions_error_handler($severity, $message, $filename, $lineno)
{
    if (error_reporting() == 0) {
   	return;
    }
    if (error_reporting() & $severity) {
   	throw new \ErrorException($message, 0, $severity, $filename, $lineno);
    }
}

chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
