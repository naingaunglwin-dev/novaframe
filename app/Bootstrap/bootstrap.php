<?php

use Nova\Facade\Bootstrap;

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
