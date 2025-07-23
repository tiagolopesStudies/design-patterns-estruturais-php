# Padrão Composite

## Problemática

## Solução

Interface que define uma composição:
```php
interface Budgetable
{
    public function value(): float;
}
```

Item que implementa a composição:
```php
class Budget implements Budgetable
{
    public function __construct(
        public float $value,
        public int $itemsCount = 1
    ) {
    }

    public function value(): float
    {
        return $this->value;
    }
}
```

Item que implementa e também utiliza a composição:
```php
class BudgetList implements Budgetable
{
    private array $items;
    public function __construct()
    {
        $this->items = [];
    }

    public function addItem(Budgetable $budget): void
    {
        $this->items[] = $budget;
    }

    public function value(): float
    {
        return array_reduce(
            array: $this->items,
            callback: fn (float $total, Budgetable $budget) => $total + $budget->value(),
            initial: 0.0
        );
    }
}
```
