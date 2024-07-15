<?php

use Nova\Facade\Event;

/*
 |----------------------------------------------------------------------
 | Before Events
 |----------------------------------------------------------------------
 |
 | Events that will load on stage before launching the application
 |
 */
Event::on('nova.before', function () {

    /*
     |---------------------------------
     | (Web) Application Events
     |---------------------------------
     */
    Event::on('nova.web', function () {

        Event::on('controller_initialize', function ($controller, $request, $response) {
            $controller->initialize($request, $response);
        });

        # Your events start here. Please do not edit above events.

    });

    /*
     |---------------------------------------------------
     | (Command-Line Interface) Application Events
     |---------------------------------------------------
     */
    Event::on('nova.cli', function () {

        Event::on('console_start', function ($output, $start, $timezone) {
            $output->write(sprintf(PHP_EOL . "<comment>CLI RunTime: %s  [%s]</comment>" . PHP_EOL, $start, $timezone));
        });

        Event::on('console_terminate', function ($output, $runtime) {
            $output->writeln(sprintf(PHP_EOL . PHP_EOL . "<info>CLI Execution Time: %.2f seconds</info>" . PHP_EOL, $runtime));
        });

        # Your events start here. Please do not edit above events.

    });
});
