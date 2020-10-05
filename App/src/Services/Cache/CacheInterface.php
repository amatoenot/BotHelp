<?php

declare(strict_types=1);


namespace App\Services\Cache;


interface CacheInterface
{
    public function delete(int $key, bool $withLock = false): void;

    public function set(int $key, int $value, bool $withLock = false): void;

    public function find(int $key, bool $withLock = false): ?int;

    public function lockCache(): void;

    public function unlockCache(): void;
}