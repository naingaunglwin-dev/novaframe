<?php

use NovaFrame\Facade\Event;

Event::on('console.start', function (\Symfony\Component\Console\Output\ConsoleOutput $output, \Symfony\Component\Console\Terminal $terminal) {
    $status = 'command started at: ' . date('Y-m-d H:i:s') . ' [' . date_default_timezone_get() . ']';
    $version = 'php ' . PHP_VERSION . ' | novaframe ' . app()->version();

    $maxWidth = $terminal->getWidth();

    $output->writeln(
        PHP_EOL
        . '<comment>' . $status . '</comment>'
        . str_repeat(' ', $maxWidth - (mb_strwidth($status) + mb_strwidth($version)))
        . '<info>' . $version . '</info>'
    );

    $output->writeln(
        PHP_EOL . '<fg=gray>' . str_repeat('-', $maxWidth) . '</>'
    );
});

Event::on('csrfValidation', function ($response, $pass) {
    if ($pass) {
        return true;
    }

    $response->setStatusCode(403);
    $response->setContent(view('errors.' . config('app.env', 'production') . '.403'));

    return $response;
});
