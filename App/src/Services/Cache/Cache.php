<?php

declare(strict_types=1);

namespace App\Services\Cache;

use App\Services\FileLocker\FileLockerInterface;

class Cache implements CacheInterface
{
    /**
     * @var string
     */
    private string $cacheFileName;
    /**
     * @var FileLockerInterface
     */
    private FileLockerInterface $fileLocker;

    /**
     * @var null|resource
     */
    private $lockedCacheFile = null;

    public function __construct(string $cacheFileName, FileLockerInterface $fileLocker)
    {
        $this->cacheFileName = $cacheFileName;
        $this->fileLocker = $fileLocker;
    }

    /**
     * @param string $key
     */
    public function delete(int $key, bool $withLock = false): void
    {
        if ($withLock) {
            $cacheFile = $this->fileLocker->lock($this->cacheFileName);
        }
        $cache = json_decode(file_get_contents($this->cacheFileName), true);
        unset($cache[$key]);
        file_put_contents($this->cacheFileName, json_encode($cache));
        if ($withLock) {
            $this->fileLocker->unlock($cacheFile);
        }
    }

    /**
     * @param string $key
     * @param int $value
     * @param bool $withLock
     */
    public function set(int $key, int $value, bool $withLock = false): void
    {
        if ($withLock) {
            $cacheFile = $this->fileLocker->lock($this->cacheFileName);
        }
        $cache = json_decode(file_get_contents($this->cacheFileName), true);
        $cache[$key] = $value;
        file_put_contents($this->cacheFileName, json_encode($cache));
        if ($withLock) {
            $this->fileLocker->unlock($cacheFile);
        }
    }

    /**
     * @param string $key
     * @param bool $withLock
     * @return int|null
     */
    public function find(int $key, bool $withLock = false): ?int
    {
        if ($withLock) {
            $cacheFile = $this->fileLocker->lock($this->cacheFileName);
        }
        $cache = json_decode(file_get_contents($this->cacheFileName), true);
        if (isset($cache[$key])) {
            if ($withLock) {
                $this->fileLocker->unlock($cacheFile);
            }
            return $cache[$key];
        }
        if ($withLock) {
            $this->fileLocker->unlock($cacheFile);
        }
        return null;
    }

    /**
     * @throws \Exception
     */
    public function lockCache(): void
    {
        if (!$cacheFile = $this->fileLocker->lock($this->cacheFileName)) {
            throw new \Exception('Ошибка при блокировке кэш файла.');
        }
        $this->lockedCacheFile = $cacheFile;
    }

    public function unlockCache(): void
    {
        if (null !== $this->lockedCacheFile) {
            $this->fileLocker->unlock($this->lockedCacheFile);
            $this->lockedCacheFile = null;
        }
    }
}