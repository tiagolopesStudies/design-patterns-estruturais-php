<?php

declare(strict_types=1);

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;
use Tiagolopes\DesignPatterns\Entity\Order\{Order};

require __DIR__ . '/../vendor/autoload.php';

if (count($argv) < 2 || count($argv) > 3) {
    echo 'Usage: php create-order.php <budget-value> [items-count]' . PHP_EOL;
    exit(1);
}

$budgetValue = (float) $argv[1];
$itemsCount  = isset($argv[2]) ? (int) $argv[2] : 1;
$budget      = new Budget(value: $budgetValue, itemsCount: $itemsCount);

for ($i = 0; $i < 100000; $i++) {
    $clientName  = 'Tiago Lopes';
    $finalizedAt = new DateTimeImmutable();
    $order       = new Order($clientName, $budget, $finalizedAt);
}

$memoryInMb = (memory_get_peak_usage() / 1024.0 / 1024.0);
echo "Memory usage: $memoryInMb KB" . PHP_EOL;
