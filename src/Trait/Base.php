<?php

namespace Uzbek\Humo\Trait;

/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/17/2022
 * Time: 3:19 PM
 */

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait Base
{
    private mixed $config;

    public ?string $originator;

    public ?string $centre_id;

    public int $max_amount_without_passport = 0;

    public function __construct()
    {
        $this->config = config('humo-svgate');
    }

    public function sendRequest(string $request_type, string $url, array $params)
    {
        $base_url = $this->config['base_url'];
        $preparedParams = $this->prepareRequestParams($params);

        return Http::withHeaders([
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json',
        ])->withToken($this->getToken())
            ->$request_type($base_url . $url, $preparedParams)
            ->throw(function ($response, $e) {
                throw new Exception($response->getBody()->getContents(), $response->status());
            })
            ->json('result');
    }

    public function sendXmlRequest(string $url_type, string $xml, string $session_id, string $method = '')
    {
        $base_urls = $this->config['base_urls'];

        return Http::withHeaders([
            'Content-Type' => 'application/xml',
            'Accept' => '*/*',
            'SOAPAction' => '""',
            'X-Request-Method' => $method,
        ])
            ->post($base_urls[$url_type], [
                'body' => $xml,
            ])
            ->throw(function ($response, $e) {
                throw new Exception($response->getBody()->getContents(), $response->status());
            })->json();
    }

    public function prepareRequestParams($params): array
    {
        return [
            'id' => Str::random(40),
            'params' => $params,
        ];
    }

    private function getToken(): string
    {
        //TODO write logic to get token
        return 'token';
    }
}
