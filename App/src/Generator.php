<?php

declare(strict_types=1);


namespace App;


use App\Services\Logger\LoggerInterface;
use App\Services\QueueManager\QueueManagerInterface;

class Generator
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var QueueManagerInterface
     */
    private QueueManagerInterface $queueManager;

    /**
     * @var int
     */
    private $eventId = 0;

    public function __construct(LoggerInterface $logger, QueueManagerInterface $queueManager)
    {
        $this->logger = $logger;
        $this->queueManager = $queueManager;
    }

    public function generate(): void
    {
        $accountId = rand(1, 1000);
        for($i = 0; $i < rand(1, 10); $i++) {
            $event = [
                'id' => $this->eventId++,
                'account_id' => $accountId
            ];
            $this->queueManager->addItem($event);
            $this->logger->log(json_encode($event, JSON_UNESCAPED_SLASHES));
        }
        echo " [". (new \DateTime('now'))->format("H:m:s.u") ."] Event batch created ", "\n";
        sleep(1);
    }
}