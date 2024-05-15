<?php

/*
 |-----------------------------------------------------------------------------------------
 | Version Check
 |-----------------------------------------------------------------------------------------
 |
 | This step verifies whether the PHP version meets the requirements of the framework.
 | If the PHP version does not meet the required criteria, an error will be displayed,
 | and the application will terminate to prevent potential compatibility issues and
 | optimal performance.
 |
 */
if (version_compare(phpversion(), '8.2', '<')) {
    exit(
        sprintf(
            "PHP version %s or newer is required. Current version: %s",
            '8.2',
            phpversion()
        )
    );
}

/*
 |------------------------------------------
 | Define Root Path of your application
 |------------------------------------------
 */
define('ROOT_PATH', dirname(__DIR__));

/*
 |-----------------------------------------------------------------------------
 | Autoload Dependencies
 |-----------------------------------------------------------------------------
 |
 | Load the Composer-generated autoload file to automatically load all the
 | necessary dependencies and classes required by the application.
 |
 */
require_once ROOT_PATH . '/vendor/autoload.php';

/*
 |-----------------------------------------------------------------------------
 | Maintenance
 |-----------------------------------------------------------------------------
 |
 | Load maintenance file first
 | to prevent some error occurring if the application is under maintenance
 |
 */
if (file_exists(ROOT_PATH . '/tmp/maintenance.php')) {
    include ROOT_PATH . '/tmp/maintenance.php';
}
