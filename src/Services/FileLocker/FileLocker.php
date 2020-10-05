<?php

declare(strict_types=1);


namespace App\Services\FileLocker;


class FileLocker implements FileLockerInterface
{
    /**
     * @param string $fileName
     * @return false|resource
     * @throws \Exception
     */
    public function lock(string $fileName)
    {
        if (!$file = fopen($fileName, 'r+')) {
            throw new \Exception("Ошибка при открытии файла для блокировки.");
        }
        while (!flock($file, LOCK_EX)) {}
        return $file;
    }

    /**
     * @param resource $file
     */
    public function unlock($file): void
    {
        if (!is_resource($file)) {
            throw new \InvalidArgumentException("Аргументом функции unlock должен быть ресурс.");
        }
        flock($file, LOCK_UN);
    }

}