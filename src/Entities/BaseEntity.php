<?php

declare(strict_types=1);

namespace Webparking\LaravelVisma\Entities;

use GuzzleHttp\Psr7\Query;
use Illuminate\Support\Collection;
use Webparking\LaravelVisma\Client;

abstract class BaseEntity
{
    protected Client $client;

    protected string $endpoint;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function baseIndex(array $queryParams = []): Collection
    {
        $finished = false;
        $currentPage = 1;
        $data = [];

        while (true !== $finished) {
            $request = $this->client->getProvider()->getAuthenticatedRequest(
                'GET',
                $this->buildUri($currentPage, $queryParams),
                $this->client->getToken()
            );

            $json = json_decode($this->client->getProvider()->getResponse($request)->getBody()->getContents());

            $data = array_merge(array_values($data), array_values($json->Data));

            ++$currentPage;
            if ($json->Meta->CurrentPage === $json->Meta->TotalNumberOfPages) {
                $finished = true;
            }
        }

        return collect($data);
    }

    protected function basePost(object $object, array $queryParams = []): object
    {
        $arr = (array) $object;
        foreach ($arr as $key => $value) {
            if (!isset($value)) {
                unset($arr[$key]);
            }
        }
        $options = [];
        $options['body'] = json_encode($arr);
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Accept'] = 'application/json';
        $request = $this->client->getProvider()->getAuthenticatedRequest(
            'POST',
            $this->buildUri(1, $queryParams, true),
            $this->client->getToken(),
            $options
        );

        return json_decode($this->client->getProvider()->getResponse($request)->getBody()->getContents());
    }

    protected function baseGet(): object
    {
        $request = $this->client->getProvider()->getAuthenticatedRequest(
            'GET',
            $this->buildUri(1),
            $this->client->getToken()
        );

        return json_decode($this->client->getProvider()->getResponse($request)->getBody()->getContents());
    }

    private function getUrlAPI(): string
    {
        if ('production' === config('visma.environment')) {
            return (string) config('visma.production.api_url');
        }

        return (string) config('visma.sandbox.api_url');
    }

    private function buildUri(int $currentPage, array $extraParams = [], bool $postUrl = false): string
    {
        $url = $this->getUrlAPI() . $this->getEndpoint();

        if ($postUrl) {
            return $url . '?' . Query::build($extraParams, false);
        }

        return $url . '?' . Query::build(array_merge(['$page' => $currentPage, '$pagesize' => 100], $extraParams), false);
    }

    private function getEndpoint(): string
    {
        if (!isset($this->endpoint)) {
            throw new \RuntimeException('Endpoint not set!');
        }

        return $this->endpoint;
    }
}
