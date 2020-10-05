<?php

declare(strict_types=1);


namespace App\Services\QueueManager;


interface QueueManagerInterface
{
    public function findAvailableItemAndDelete(): ?array;

    public function addItem(array $item): void;
}