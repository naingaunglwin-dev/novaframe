<?php

use Nova\Facade\Bootstrap;
use Nova\Service\Bootstrap\Web\Bag as WebBag;
use Nova\Service\Bootstrap\CLI\Bag as CliBag;

Bootstrap::before(
    fn () => Bootstrap::web(function () {
        // any bootstrapping process before application is ready to launch on web
    })->cli(function () {
        // any bootstrapping process before application is ready to launch on cli
    })->autoload(
        [
            APP_PATH . 'Routes/web.php',
            // Do not remove above files
            // You can start register your autoload files here
        ]
    )
);
