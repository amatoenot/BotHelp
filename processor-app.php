<?php

declare(strict_types=1);

require './vendor/autoload.php';

$logger = new App\Services\Logger\Logger('./workerLog.txt');
$fileLocker = new \App\Services\FileLocker\FileLocker();
$cache = new \App\Services\Cache\Cache('./cache.txt', $fileLocker);
$queueManager = new \App\Services\QueueManager\QueueManager('./queue.txt', $fileLocker, $cache);

$processor = new App\Processor($logger, $queueManager, $cache);

while (true) {
    $processor->process();
}
