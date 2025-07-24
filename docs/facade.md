# Padrão Facade

## Problemática

Código com lógica complexa e extensa:
```php
class DiscountCalculator
{
    public function calculate(Budget $budget): float
    {
        $discount = (new DiscountMoreThan5Items(
            new DiscountMoreThan500Reais
        ))->calculate($budget);

        $filepath = __DIR__ . '/../../logs/discounts.log';
        if (! file_exists($filepath)) {
            touch($filepath);
        }

        $status   = get_class($budget->status);
        $datetime = date('Y-m-d H:i:s');

        $content = <<<TEXT
            =========================
            {$datetime}
            value: {$budget->value}
            discount: {$discount}
            items_count: {$budget->itemsCount}
            status: {$status}
        TEXT;

        file_put_contents(filename: $filepath, data: $content, flags: FILE_APPEND);

        // More complex code

        return $discount;
    }
}
```

## Solução

Classe que implementa a fachada, abstraindo a complexidade e fornecendo uma interface simples:
```php
class DiscountLogger
{
    public static function saveLog(Budget $budget, float $discount): void
    {
        $filepath = __DIR__ . '/../../logs/discounts.log';
        if (! file_exists($filepath)) {
            touch($filepath);
        }

        $status   = get_class($budget->status);
        $datetime = date('Y-m-d H:i:s');

        $content = <<<TEXT
            =========================
            {$datetime}
            value: {$budget->value}
            discount: {$discount}
            items_count: {$budget->itemsCount}
            status: {$status}
        TEXT;

        file_put_contents(filename: $filepath, data: $content, flags: FILE_APPEND);

        // More complex code
    }
}
```

Substituição no código original:
```php
class DiscountCalculator
{
    public function calculate(Budget $budget): float
    {
        $discount = (new DiscountMoreThan5Items(
            new DiscountMoreThan500Reais
        ))->calculate($budget);

        DiscountLogger::saveLog($budget, $discount);

        return $discount;
    }
}
```
