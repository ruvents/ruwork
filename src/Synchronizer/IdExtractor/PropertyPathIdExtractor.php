<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\IdExtractor;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class PropertyPathIdExtractor implements IdExtractorInterface
{
    private $path;
    private $accessor;

    public function __construct(string $path, PropertyAccessorInterface $accessor = null)
    {
        $this->path = $path;
        $this->accessor = $accessor ?? PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function extractId($item)
    {
        return $this->accessor->getValue($item, $this->path);
    }
}
