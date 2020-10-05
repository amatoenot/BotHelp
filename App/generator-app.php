<?php

declare(strict_types=1);

require './vendor/autoload.php';

$logger = new App\Services\Logger\Logger('./generatorLog.txt');
$fileLocker = new \App\Services\FileLocker\FileLocker();
$cache = new \App\Services\Cache\Cache('./cache.txt', $fileLocker);
$queueManager = new \App\Services\QueueManager\QueueManager('./queue.txt', $fileLocker, $cache);

$generator = new App\Generator($logger, $queueManager);

while (true) {
    $generator->generate();
}