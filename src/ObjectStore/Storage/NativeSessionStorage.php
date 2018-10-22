<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Storage;

final class NativeSessionStorage implements StorageInterface
{
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $this->startSession();

        return $_SESSION[$this->key] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function set($data): void
    {
        $this->startSession();

        $_SESSION[$this->key] = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->startSession();

        unset($_SESSION[$this->key]);
    }

    private function startSession(): void
    {
        if (PHP_SESSION_NONE === \session_status()) {
            \session_start();
        }
    }
}
