<?php

/*
 |========================================================================================
 | Version Check
 |========================================================================================
 |
 | This step verifies whether the php version meets the requirements of the framework.
 | If the php version does not meet the required criteria, an error will be displayed,
 | and the application will terminate to prevent potential compatibility issues and
 | optimal performance.
 |
 */
if (version_compare(phpversion(), '8.2', '<')) {
    exit('PHP version 8.2 or newer is required. Current version: ' . phpversion());
}

# define a short definition for DIRECTORY_SEPARATOR
define('DS', DIRECTORY_SEPARATOR);

/*
 |========================================================================
 | Root Directory Constant Definition
 |========================================================================
 |
 | Set DIR_ROOT aas the parent directory of the current directory,
 | establishing the base path for the application structure.
 |
 */
define('DIR_ROOT', dirname(__DIR__) . DS);

/*
 |========================================================================
 | Framework's Constants Inclusion
 |========================================================================
 |
 | Loads the constant.php file from the framework's lib directory,
 | bringing in essential constant definitions for the framework.
 |
 */
require_once join(DS, [DIR_ROOT, 'lib', 'constant.php']);

/*
 |======================================================================
 | Composer Autoloader Initialization
 |======================================================================
 |
 | Includes Composer's autoload file to enable PSR-4 autoloading,
 | automatically loading all vendor dependencies and classes
 |
 */
require DIR_ROOT . 'vendor' . DS . 'autoload.php';

/*
 |=================================================================================================
 | Boot Application
 |=================================================================================================
 |
 | Loads the `application.php` bootstrap file and calls
 | the `boot` method of the `NovaFrame\Kernel` class to finalize
 | the bootstrapping process
 |
 | You can pass the new instance of \NovaFrame\Maintenance to boot()
 | in the place of request to set application under maintenance
 | eg:
 | (require DIR_BOOTSTRAP . 'application.php')
 |   ->boot(new \NovaFrame\Maintenance(DIR_ROOT . 'storage' . DS . 'maintenance.php'));
 |
 | @see bootstrap/application.php
 | @see \NovaFrame\Kernel::boot()
 |
 */
(require DIR_BOOTSTRAP . 'application.php')
    ->boot(\NovaFrame\Http\Request::createFromGlobals());