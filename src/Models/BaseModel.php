<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/17/2022
 * Time: 3:17 PM
 */

namespace Uzbek\Humo\Models;

use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Nonstandard\Uuid;
use Uzbek\Humo\Exceptions\Exception;

class BaseModel
{
    private mixed $config;

    public ?string $originator;

    public ?string $centre_id;

    public int $max_amount_without_passport = 0;

    public ?string $session_id = null;

    public function __construct()
    {
        $this->config = config('humo-svgate');
    }

    public function sendRequest(string $url_type, string $request_type, string $url, array $params)
    {
        $preparedParams = $this->prepareRequestParams($params);

        $request = Http::withHeaders([
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json',
        ])
            ->withToken($this->config['token'])
            ->$request_type($this->getBaseUrls()[$url_type].$url, $preparedParams)
            ->throw(function ($response, $e) {
                throw new Exception($response->getBody()->getContents(), $response->status());
            })
            ->json('result');

        return $request;
    }

    public function sendXmlRequest(string $url_type, string $xml, string $session_id, string $method = '')
    {
        $request = Http::withHeaders([
            'Content-Type' => 'application/xml',
            'Accept' => '*/*',
            'SOAPAction' => '""',
            'X-Request-Method' => $method,
        ])
            ->withBasicAuth($this->config['username'], $this->config['password'])
            ->post($this->getBaseUrls()[$url_type], [
                'body' => $xml,
            ])
            ->throw(function ($response, $e) {
                throw new Exception($response->getBody()->getContents(), $response->status());
            })->json();

        return $request;
    }

    public function prepareRequestParams($params): array
    {
        return [
            'id' => Uuid::uuid4(),
            'params' => $params,
        ];
    }

    private function getMaxAmountWithoutPassport(): int
    {
        return $this->max_amount_without_passport ?? $this->config['max_amount_without_passport'];
    }

    public function getSessionID(): string
    {
        return $this->session_id ?? $this->getNewSessionID();
    }

    public function getNewSessionID(): string
    {
        try {
            $this->session_id = (string) \Ramsey\Uuid\Uuid::uuid4();
        } catch (Exception $e) {
            $this->session_id = (string) time();
        }

        return $this->session_id;
    }

    /**
     * Проверка карт на корпоративная
     *
     * @param  string  $card
     * @return bool
     */
    public function isCorp(string $card): bool
    {
        /*TODO to correct MKB (from SQB)*/
        return substr($card, 6, 2) === '02';
    }

    public function isMKB(string $card): bool
    {
        /*TODO to correct MKB (from SQB)*/
        return substr($card, 4, 2) === '02';
    }

    public function getBaseUrls(): array
    {
        return $this->config['base_urls'];
    }
}
