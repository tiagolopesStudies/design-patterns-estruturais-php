# Padrão Decorator

## Problemática

Classe de imposto:
```php
class Icms implements TaxInterface
{
    public function calculate(Budget $budget): float
    {
        return $budget->value * 0.1;
    }
}
```

Classe de outro imposto:
```php
class Iss implements TaxInterface
{
    public function calculate(Budget $budget): float
    {
        return $budget->value * 0.07;
    }
}
```

Classe com a junção das duas classes:
```php
class IcmsWithIss implements TaxInterface
{
    public function calculate(Budget $budget): float
    {
        return (new Icms())->calculate($budget) + (new Iss())->calculate($budget);
    }
}
```

## Solução
Classe abstrata que permite acrescentar a funcionalidade de um imposto a um outro imposto:
```php
abstract class Tax
{
    public function __construct(private readonly ?Tax $anotherTask = null)
    {
    }

    abstract protected function calculateSpecificTax(Budget $budget): float;

    public function calculate(Budget $budget): float
    {
        return $this->calculateSpecificTax($budget) + $this->anotherTask?->calculate($budget) ?? 0;
    }
}
```

Implementação do método abstrato:
```php
class Icms extends Tax
{
    public function calculateSpecificTax(Budget $budget): float
    {
        return $budget->value * 0.1;
    }
}
```

Uso na prática:
```php
$taxCalculator = new TaxCalculator();
$icms          = new Icms;
$withIptu      = new Iptu($icms);
$withIss       = new Iss($withIptu);


$taxCalculator->calculate(
    budget: $budget,
    tax: $withIss
);
```
