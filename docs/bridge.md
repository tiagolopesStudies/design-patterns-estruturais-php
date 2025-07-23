# Padrão Bridge

## Problemática

Implementação de classe para exportar orçamento em Zip
```php
class BudgetZip
{
    public static function export(Budget $budget): void
    {
        $filename = 'budget-' . date('Y-m-d-H-i-s') . '.zip';
        $filepath = __DIR__ . "/../../temp/$filename";

        $zip = new ZipArchive();
        $zip->open($filepath, ZipArchive::CREATE);
        $zip->addFromString('budget.serialize', serialize($budget));
        $zip->close();
    }
}
```

Implementação de classe para exportar orçamento em XML
```php
class BudgetXml
{
    public static function export(Budget $budget): void
    {
        $xml = new SimpleXMLElement('<budget />');

        $xml->addChild(qualifiedName: 'value', value: (string) $budget->value);
        $xml->addChild(qualifiedName: 'items_count', value: (string) $budget->itemsCount);
        $xml->addChild(qualifiedName: 'status', value: get_class($budget->status));

        $result = $xml->asXML();
        if (! $result) {
            throw new RuntimeException('Error exporting budget to XML.');
        }

        $filename = 'budget-' . date('Y-m-d-H-i-s') . '.xml';
        $filepath = __DIR__ . "/../../temp/$filename";
        file_put_contents($filepath, $result);
    }
}
```

## Solução

Criação de interface para representar o conteúdo do arquivo:
```php
interface ExportedContent
{
    public function getContent(): array;
}
```

Criação de interface para representar a forma de exportação do arquivo:
```php
interface FileExporter
{
    public function export(ExportedContent $content): string;
}
``` 

Implementando classe que faz a exportação do arquivo:
```php
class ZipExporter implements FileExporter
{
    public function __construct(public string $filename)
    {
    }

    public function export(ExportedContent $content): string
    {
        $filepath = __DIR__ . "/../../temp/$this->filename";

        $zip = new ZipArchive();
        $zip->open($filepath, ZipArchive::CREATE);
        $zip->addFromString('content.serialize', serialize($content));
        $zip->close();

        return realpath($filepath);
    }
}
```

Implementando classe que recupera o conteúdo do arquivo:
```php
readonly class ExportedBudget implements ExportedContent
{
    public function __construct(private Budget $budget)
    {
    }

    public function getContent(): array
    {
        return [
            'value' => $this->budget->value,
            'items_count' => $this->budget->itemsCount,
            'status' => get_class($this->budget->status),
        ];
    }
}
```
