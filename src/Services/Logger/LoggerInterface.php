<?php

declare(strict_types=1);


namespace App\Services\Logger;


interface LoggerInterface
{
    public function log(string $data): void;
}