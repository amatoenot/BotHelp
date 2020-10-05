<?php

declare(strict_types=1);


namespace App\Services\QueueManager;


use App\Services\Cache\CacheInterface;
use App\Services\FileLocker\FileLockerInterface;

class QueueManager implements QueueManagerInterface
{
    /**
     * @var string
     */
    private string $queueFileName;
    /**
     * @var FileLockerInterface
     */
    private FileLockerInterface $fileLocker;
    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    public function __construct(string $queueFileName, FileLockerInterface $fileLocker, CacheInterface $cache)
    {
        $this->queueFileName = $queueFileName;
        $this->fileLocker = $fileLocker;
        $this->cache = $cache;
    }

    /**
     * @return array|null
     */
    public function findAvailableItemAndDelete(): ?array
    {
        $queueFile = $this->fileLocker->lock($this->queueFileName);
        $this->cache->lockCache();
        $queueFileAsArray = file($this->queueFileName);
        foreach ($queueFileAsArray as $key => $queueFileRow) {
            $item = json_decode(trim($queueFileRow), true);
            if (null === $this->cache->find($item['account_id'])) {
                $this->cache->set($item['account_id'], 1);
                unset($queueFileAsArray[$key]);
                file_put_contents($this->queueFileName, implode("", $queueFileAsArray));
                $this->fileLocker->unlock($queueFile);
                $this->cache->unlockCache();
                return $item;
            }
        }
        $this->fileLocker->unlock($queueFile);
        $this->cache->unlockCache();
        return null;
    }

    public function addItem(array $item): void
    {
        file_put_contents($this->queueFileName, json_encode($item, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

}