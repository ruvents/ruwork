<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Source;

use Ruwork\UploadBundle\Source\Handler\SourceHandlerInterface;

final class ResolvedSource
{
    private $handler;
    private $source;
    private $attributes;

    public function __construct(SourceHandlerInterface $handler, $source)
    {
        $this->handler = $handler;
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getAttributes(): array
    {
        if (null === $this->attributes) {
            $this->attributes = $this->handler->getAttributes($this->source);
        }

        return $this->attributes;
    }

    public function write(string $target): void
    {
        $this->handler->write($this->source, $this->attributes, $target);
    }
}
