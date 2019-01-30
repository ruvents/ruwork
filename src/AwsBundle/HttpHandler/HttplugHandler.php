<?php

declare(strict_types=1);

namespace Ruwork\AwsBundle\HttpHandler;

use function GuzzleHttp\Promise\promise_for;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;
use Http\Client\Exception\HttpException;
use Http\Client\Exception\NetworkException;
use Http\Client\HttpAsyncClient;
use Http\Discovery\HttpAsyncClientDiscovery;
use Psr\Http\Message\RequestInterface;

final class HttplugHandler
{
    private $client;

    public function __construct(?HttpAsyncClient $client = null)
    {
        $this->client = $client ?? HttpAsyncClientDiscovery::find();
    }

    public function __invoke(RequestInterface $request): PromiseInterface
    {
        $promise = $this->client->sendAsyncRequest($request);
        $promise->wait(false);

        return promise_for($promise)
            ->otherwise(static function (\Throwable $exception): RejectedPromise {
                $reason = [
                    'exception' => $exception,
                    'connection_error' => $exception instanceof NetworkException,
                    'response' => null,
                ];

                if ($exception instanceof HttpException) {
                    $reason['response'] = $exception->getResponse();
                }

                return new RejectedPromise($reason);
            });
    }
}
