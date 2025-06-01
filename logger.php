<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require __DIR__ . '/vendor/autoload.php';

function getLogger($channel = 'gibbon') {
    $log = new Logger($channel);

    // Save logs in /logs/app.log (create the folder if needed)
    $log->pushHandler(new StreamHandler(__DIR__ . '/logs/app.log', Logger::DEBUG));

    return $log;
}
