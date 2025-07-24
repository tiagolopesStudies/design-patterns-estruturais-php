# Padrão Flyweight

## Problemática

Lógica com um alto consumo de memória por conta de alocação de várias variáveis:
```php
$budgetValue = 5000.00;
$itemsCount  = 2;
$budget      = new Budget(value: $budgetValue, itemsCount: $itemsCount);
$orders      = [];

for ($i = 0; $i < 10000; $i++) {
    $clientName  = uniqid();
    $finalizedAt = new DateTimeImmutable();
    $orders[]    = Order::create($clientName, $budget, $finalizedAt);
}

$memoryInMb = round(num: (memory_get_peak_usage() / 1024.0 / 1024.0), precision: 2);
echo "Memory usage: $memoryInMb MB" . PHP_EOL; // 5.59 MB
```

## Solução

Criação de classe que possui os dados extrínsecos de um pedido (order):
```php
readonly class OrderTemplate
{
    public function __construct(
        public string $clientName,
        public ?DateTimeImmutable $finalizedAt = null
    ) {
    }
}
```

Criando factory que retorna uma nova instância da classe e faz o cache dos dados extrínsecos:
```php
class OrderFactory
{
    private array $templatesCache;
    public function __construct()
    {
        $this->templatesCache = [];
    }

    public function make(
        string $clientName,
        Budget $budget,
        ?DateTimeImmutable $finalizedAt = null
    ): Order {
        $template = $this->generateTemplate($clientName, $finalizedAt);

        return Order::createFromTemplate($template, $budget);
    }

    private function generateTemplate(string $clientName, ?DateTimeImmutable $finalizedAt = null): OrderTemplate
    {
        $hash = md5($clientName . ($finalizedAt?->format('Y-m-d') ?? 'not-finalized'));

        if (!isset($this->templatesCache[$hash])) {
            $this->templatesCache[$hash] = new OrderTemplate($clientName, $finalizedAt);
        }

        return $this->templatesCache[$hash];
    }
}
```

Reutilizando o objeto ao invés de criar novas variáveis para o mesmo dado:
```php
$budgetValue = (float) $argv[1];
$itemsCount  = isset($argv[2]) ? (int) $argv[2] : 1;
$budget      = new Budget(value: $budgetValue, itemsCount: $itemsCount);
$orders       = [];
$orderFactory = new OrderFactory();

for ($i = 0; $i < 10000; $i++) {
    $orders[] = $orderFactory->make(
        clientName: 'Tiago Lopes',
        budget: $budget,
        finalizedAt: new DateTimeImmutable()
    );
}

$memoryInMb = round(num: (memory_get_peak_usage() / 1024.0 / 1024.0), precision: 2);
echo "Memory usage: $memoryInMb MB" . PHP_EOL;
```
