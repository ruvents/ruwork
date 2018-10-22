<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Storage;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SymfonySessionStorage implements StorageInterface
{
    private $session;
    private $key;

    public function __construct(SessionInterface $session, string $key)
    {
        $this->session = $session;
        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $this->startSession();

        return $this->session->get($this->key);
    }

    /**
     * {@inheritdoc}
     */
    public function set($data): void
    {
        $this->startSession();

        $this->session->set($this->key, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->startSession();

        $this->session->remove($this->key);
    }

    private function startSession(): void
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
    }
}
