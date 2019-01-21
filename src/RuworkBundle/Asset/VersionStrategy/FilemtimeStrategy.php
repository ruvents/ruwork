<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Asset\VersionStrategy;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

final class FilemtimeStrategy implements VersionStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getVersion($path)
    {
        return (string) @filemtime((string) $path);
    }

    /**
     * {@inheritdoc}
     */
    public function applyVersion($path)
    {
        if ('' !== $version = $this->getVersion($path)) {
            $path .= '?t='.$version;
        }

        return $path;
    }
}
