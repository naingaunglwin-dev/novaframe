<?php

$basePath = __DIR__ . "/..";

if (!file_exists("$basePath/.env")) {
    copy("$basePath/.env.example", "$basePath/.env");
    echo '> .env copied.' . PHP_EOL;
}

$cmd = 'php nova key:generate';
exec($cmd);
echo '> ' . $cmd . PHP_EOL;
