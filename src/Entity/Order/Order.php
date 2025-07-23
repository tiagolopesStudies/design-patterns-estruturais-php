<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Order;

use DateTimeImmutable;
use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class Order
{
    public function __construct(
        public string $clientName,
        public Budget $budget,
        public ?DateTimeImmutable $finalizedAt = null
    ) {
    }
}
