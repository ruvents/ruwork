<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Basket;

use Psr\Container\ContainerInterface;
use RunetId\Client\RunetIdClient;
use Ruwork\RunetIdBundle\Basket\Handler\HandlerInterface;
use Ruwork\RunetIdBundle\Basket\Loader\LoaderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Basket implements BasketInterface
{
    private $client;
    private $payerRunetId;
    private $payCollection;
    private $loaders;
    private $handlers;

    public function __construct(
        RunetIdClient $client,
        int $payerRunetId,
        ContainerInterface $loaders,
        ContainerInterface $handlers
    ) {
        $this->client = new Client($client, $payerRunetId);
        $this->payerRunetId = $payerRunetId;
        $this->payCollection = new PayCollection($this->client);
        $this->loaders = $loaders;
        $this->handlers = $handlers;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayerRunetId(): int
    {
        return $this->payerRunetId;
    }

    /**
     * {@inheritdoc}
     */
    public function load(string $class, array $options = [])
    {
        /** @var LoaderInterface $loader */
        $loader = $this->loaders->get($class);
        $options = $this->resolveOptions($loader, $options);
        $element = $loader->load($options, $this, $this->payCollection);

        if (!$element instanceof $class) {
            throw new \UnexpectedValueException(sprintf(
                'Loaded element is expected to be an instance of "%s".',
                $class
            ));
        }

        return $element;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(string $handler, array $options = []): void
    {
        /** @var HandlerInterface $handler */
        $handler = $this->handlers->get($handler);
        $options = $this->resolveOptions($handler, $options);
        $handler->handle($options, $this, $this->client);
    }

    /**
     * @param HandlerInterface|LoaderInterface $configurator
     */
    private function resolveOptions($configurator, array $options): array
    {
        $resolver = new OptionsResolver();
        $configurator->configureOptions($resolver);

        return $resolver->resolve($options);
    }
}
