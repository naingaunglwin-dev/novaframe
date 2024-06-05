<?php

use Nova\Facade\Bootstrap;

Bootstrap::before(
    fn () => Bootstrap::with(function () {
        // any bootstrapping process before application goes to launch
    })->autoload(
        [
            APP_PATH . 'Routes/web.php',
            // Do not remove above files
            // You can start register your autoload files here
        ]
    )
);
