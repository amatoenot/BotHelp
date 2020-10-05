<?php

declare(strict_types=1);


namespace App\Services\FileLocker;


interface FileLockerInterface
{
    /**
     * @param string $fileName
     * @return false|resource
     */
    public function lock(string $fileName);

    /**
     * @param resource $file
     */
    public function unlock($file): void;
}