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
    define('BOOTSTRAP_PATH', str_replace('\\', '/', APP_PATH . 'Bootstrap/'));
}

/*
 |----------------------------------------------------------------------------------------
 | Set Default Timezone
 |----------------------------------------------------------------------------------------
 |
 | This line of code sets the default timezone for the application based on the value
 | retrieved from the application configuration. If the timezone is not specified in
 | the configuration, it defaults to UTC timezone. This ensures that the application
 | operates with a consistent timezone setting.
 |
 */
date_default_timezone_set(config('app.timezone', 'UTC'));

return \Nova\Foundation\Application::getInstance();
