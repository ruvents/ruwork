<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Source;

use Ruwork\UploadBundle\Source\Handler\SourceHandlerInterface;

final class ResolvedSource implements ResolvedSourceInterface
{
    private $source;
    private $handler;
    private $attributes;
    private $tmpPath;
    private $path;
    private $absolutePath;
    private $saveFromSource;
    private $saved = false;

    public function __construct(
        $source,
        SourceHandlerInterface $handler,
        array $attributes,
        string $tmpPath,
        string $path,
        string $absolutePath,
        bool $saveFromSource
    ) {
        $this->source = $source;
        $this->handler = $handler;
        $this->attributes = $attributes;
        $this->tmpPath = $tmpPath;
        $this->path = $path;
        $this->absolutePath = $absolutePath;
        $this->saveFromSource = $saveFromSource;
    }

    public function __destruct()
    {
        $this->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function getTmpPath(): string
    {
        return $this->tmpPath;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function isSaved(): bool
    {
        return $this->saved;
    }

    /**
     * {@inheritdoc}
     */
    public function save(): void
    {
        if ($this->saved) {
            throw new \RuntimeException('Already saved.');
        }

        if ($this->saveFromSource) {
            $this->handler->write($this->source, $this->absolutePath);
        } else {
            $dir = \dirname($this->absolutePath);

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            rename($this->tmpPath, $this->absolutePath);
        }

        chmod($this->absolutePath, 0644);

        $this->saved = true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        @unlink($this->tmpPath);
    }
}
