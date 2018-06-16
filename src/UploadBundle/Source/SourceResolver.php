<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Source;

use Ruwork\UploadBundle\Source\Handler\SourceHandlerInterface;

final class SourceResolver implements SourceResolverInterface
{
    private $handlers;

    /**
     * @param SourceHandlerInterface[] $handlers
     */
    public function __construct(iterable $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($source): ResolvedSource
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($source)) {
                return new ResolvedSource($handler, $source);
            }
        }

        throw new \RuntimeException('No handler.');
    }
}
