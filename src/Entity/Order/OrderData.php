<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Order;

use DateTimeImmutable;

readonly class OrderData
{
    public function __construct(
        public string $clientName,
        public ?DateTimeImmutable $finalizedAt = null
    ) {
    }
}
