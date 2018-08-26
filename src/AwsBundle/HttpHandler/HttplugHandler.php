<?php

declare(strict_types=1);

namespace Ruwork\AwsBundle\HttpHandler;

use GuzzleHttp\Promise\PromiseInterface;
use Http\Client\HttpAsyncClient;
use Http\Discovery\HttpAsyncClientDiscovery;
use Psr\Http\Message\RequestInterface;
use function guzzlehttp\Promise\promise_for;

final class HttplugHandler
{
    private $client;

    public function __construct(?HttpAsyncClient $client = null)
    {
        $this->client = $client ?? HttpAsyncClientDiscovery::find();
    }

    public function __invoke(RequestInterface $request): PromiseInterface
    {
        return promise_for($this->client->sendAsyncRequest($request));
    }
}
