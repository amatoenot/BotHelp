<?php

declare(strict_types=1);

namespace App;

use App\Services\Cache\CacheInterface;
use App\Services\Logger\LoggerInterface;
use App\Services\QueueManager\QueueManagerInterface;

class Processor
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var QueueManagerInterface
     */
    private QueueManagerInterface $queryManager;
    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    public function __construct(
        LoggerInterface $logger,
        QueueManagerInterface $queryManager,
        CacheInterface $cache
    )
    {
        $this->logger = $logger;
        $this->queryManager = $queryManager;
        $this->cache = $cache;
    }

    public function process(): void
    {
        while(!$event = $this->queryManager->findAvailableItemAndDelete()) {}
        echo " [". (new \DateTime('now'))->format("H:m:s.u") ."] Received ", json_encode($event), "\n";
        sleep(rand(1,3));
        $this->logger->log(json_encode($event, JSON_UNESCAPED_SLASHES));
        $this->cache->delete($event['account_id'], true);
        echo " [". (new \DateTime('now'))->format("H:m:s.u") ."] Handled ", "\n";
    }
}