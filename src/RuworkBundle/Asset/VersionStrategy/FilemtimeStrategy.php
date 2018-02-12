<?php

namespace Ruvents\RuworkBundle\Asset\VersionStrategy;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

class FilemtimeStrategy implements VersionStrategyInterface
{
    private $webDir;

    public function __construct(string $webDir)
    {
        $this->webDir = $webDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion($path)
    {
        $filePath = $this->webDir.'/'.ltrim($path, '/');

        return file_exists($filePath) ? filemtime($filePath) : '';
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
