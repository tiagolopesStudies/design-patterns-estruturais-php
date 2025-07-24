# Padrão Proxy

## Problemática

```php
public function value(): float
{
    sleep(2); // simulates the response time of an API

    return array_reduce(
        array: $this->items,
        callback: fn (float $total, Budgetable $budget) => $total + $budget->value(),
        initial: 0.0
    );
}
```

## Solução

Classe que implementa um proxy de cache:
```php
class BudgetListCacheProxy extends BudgetList
{
    private ?float $cachedValue = null;

    public function __construct(private readonly BudgetList $budgetList)
    {
        parent::__construct();
    }

    public function addItem(Budgetable $budget): void
    {
        throw new Exception('Could not add item to a cached proxy budget list.');
    }

    public function value(): float
    {
        if ($this->cachedValue === null) {
            $this->cachedValue = $this->budgetList->value();
        }

        return $this->cachedValue;
    }
}
```

Utilização:
```php
$cacheProxy = new BudgetListCacheProxy($budgetList);

echo $cacheProxy->value() . PHP_EOL;
echo $cacheProxy->value() . PHP_EOL;
echo $cacheProxy->value() . PHP_EOL;
```
