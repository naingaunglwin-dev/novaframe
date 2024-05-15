<?php

if (!defined('APP_PATH')) {
    define('APP_PATH', str_replace('\\', '/', ROOT_PATH) . '/app/');
};

if (!defined('NOVA_PATH')) {
    define('NOVA_PATH', str_replace('\\', '/', dirname(__FILE__, 2)) . '/');
}

if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', str_replace('\\', '/', ROOT_PATH) . '/public/');
}

if (!defined('TMP_PATH')) {
    define('TMP_PATH', str_replace('\\', '/', ROOT_PATH) . '/tmp/');
}

if (!defined('BOOTSTRAP_PATH')) {
    define('BOOTSTRAP_PATH', str_replace('\\', '/', dirname(__DIR__)));
}

$app = \Nova\Foundation\Application::getInstance();

/*
 |----------------------------------------------------------------------------------------
 | Register Exception Class
 |----------------------------------------------------------------------------------------
 |
 | The framework's default error handler class is registered with the
 | container. This class is responsible for managing and handling any exceptions
 | that occur during the execution of the application. By registering the
 | ExceptionInterface and Exception classes, developers can leverage the framework's
 | built-in error handling mechanisms to ensure robust error reporting and recovery.
 |
 */
$app->singleton(
    Nova\Exception\HandlerInterface::class,
    Nova\Exception\Handler::class
);

require_once APP_PATH . 'Routes/web.php';

return $app;
