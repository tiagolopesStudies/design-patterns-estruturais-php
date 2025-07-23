# Padrão Adapter

Problemática:

```php
readonly class BudgetRegister
{
    public function register(Budget $budget): array
    {
        $curl = curl_init($url);
        curl_setopt(handle: $curl, option: CURLOPT_POST, value: true);
        curl_setopt(handle: $curl, option: CURLOPT_POSTFIELDS, value: $data);
        curl_setopt(handle: $curl, option: CURLOPT_RETURNTRANSFER, value: true);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode(json: $response, associative: true);
    }
}
```

Interface do Adapter:
```php
interface HttpAdapter
{
    public function post(string $url, array $data): array;
}

```

Implementação do Adapter:
```php
class CurlHttpAdapter implements HttpAdapter
{
    public function post(string $url, array $data): array
    {
        try {
            $curl = curl_init($url);
            curl_setopt(handle: $curl, option: CURLOPT_POST, value: true);
            curl_setopt(handle: $curl, option: CURLOPT_POSTFIELDS, value: $data);
            curl_setopt(handle: $curl, option: CURLOPT_RETURNTRANSFER, value: true);
            $response = curl_exec($curl);
            curl_close($curl);

            if ($response === false) {
                throw new Exception();
            }

            return json_decode(json: $response, associative: true);
        } catch (Throwable) {
            return [
                'error' => 'API not available.'
            ];
        }
    }
}
```

Implementando um novo adapter a partir da mesma interface:
```php
readonly class GuzzleHttpAdapter implements HttpAdapter
{
    public function __construct(private \GuzzleHttp\Client $client)
    {
    }

    public function post(string $url, array $data): array
    {
        try {
            $response = $this->client->post(
                uri: $url,
                options: [
                    'json' => $data,
                ]
            );

            if ($response->getStatusCode() !== 201) {
                throw new Exception();
            }

            return json_decode(json: $response->getBody()->getContents(), associative: true);
        } catch (Throwable) {
            return [
                'error' => 'API not available.'
            ];
        }
    }
}
```
