<?php

declare(strict_types=1);

namespace Ruwork\WizardBundle\Storage;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionStorage implements StorageInterface
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key): bool
    {
        return $this->session->has($this->getKey($key));
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        return $this->session->get($this->getKey($key));
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value): void
    {
        $this->session->set($this->getKey($key), $value);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        $this->session->remove($this->getKey($key));
    }

    private function getKey(string $key): string
    {
        return 'ruwork_wizard.'.$key;
    }
}
