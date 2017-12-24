<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\Config\Model;

abstract class AbstractConfig implements \Serializable
{
    private $locked = false;

    private $data = [];

    public function __isset($name)
    {
        return array_key_exists($name, $this->data) || !$this->locked;
    }

    public function __get($name)
    {
        if (!$this->__isset($name)) {
            throw new \InvalidArgumentException(sprintf('Property %s::$%s is not defined.', get_class($this), $name));
        }

        return $this->data[$name] ?? null;
    }

    public function __set($name, $value): void
    {
        if ($this->locked) {
            throw new \LogicException(sprintf('Config %s is closed for modification.', get_class($this)));
        }

        $this->data[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $this->locked = true;

        return serialize($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        $this->data = unserialize($serialized);
        $this->locked = true;
    }
}
