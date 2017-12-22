<?php

namespace Ruwork\DoctrineFilterBundle\Tests\Fixtures;

use Psr\Container\ContainerInterface;

class PsrContainer implements ContainerInterface
{
    private $container = [];

    public function set(string $name, $data)
    {
        $this->container[$name] = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new \OutOfBoundsException();
        }

        return $this->container[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function has($id): bool
    {
        return array_key_exists($id, $this->container);
    }
}
