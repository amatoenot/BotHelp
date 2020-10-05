<?php

declare(strict_types=1);

namespace App\Services\Logger;

class Logger implements LoggerInterface
{
    /**
     * @var string
     */
    private string $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function log(string $data): void
    {
        file_put_contents($this->fileName, $data . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

}