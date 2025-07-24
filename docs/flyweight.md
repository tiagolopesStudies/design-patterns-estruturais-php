# Padrão Flyweight

## Problemática

Lógica com um alto consumo de memória por conta de alocação de várias variáveis:
```php
$budgetValue = 5000.00;
$itemsCount  = 2;
$budget      = new Budget(value: $budgetValue, itemsCount: $itemsCount);

for ($i = 0; $i < 100000; $i++) {
    $clientName  = uniqid();
    $finalizedAt = new DateTimeImmutable();
    $order       = new Order($clientName, $budget, $finalizedAt);
}

$memoryInMb = (memory_get_peak_usage(real_usage: true) / 1024.0);
echo "Memory usage: $memoryInMb KB" . PHP_EOL; // High memory usage
```

## Solução

Criação de classe que possui os dados extrínsecos de um pedido (order):
```php
readonly class OrderData
{
    public function __construct(
        public string $clientName,
        public ?DateTimeImmutable $finalizedAt = null
    ) {
    }
}
```

Alterando a classe base para utilizar a classe com os dados extrínsecos:
```php
class Order
{
    public function __construct(
        public Budget $budget,
        public OrderData $orderData
    ) {
    }
}
```

Reutilizando objeto ao invés de criar novas variáveis para o mesmo dado:
```php
$budgetValue = (float) $argv[1];
$itemsCount  = isset($argv[2]) ? (int) $argv[2] : 1;
$budget      = new Budget(value: $budgetValue, itemsCount: $itemsCount);
$orderData   = new OrderData(
    clientName: uniqid(),
    finalizedAt: new DateTimeImmutable(),
);

for ($i = 0; $i < 100000; $i++) {
    $order = new Order($budget, $orderData);
}

$memoryInMb = (memory_get_peak_usage() / 1024.0 / 1024.0);
echo "Memory usage: $memoryInMb KB" . PHP_EOL; // Low memory usage
```
