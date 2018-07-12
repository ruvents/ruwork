<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Manager;

use Ruwork\UploadBundle\Exception\NotRegisteredException;
use Ruwork\UploadBundle\Metadata\UploadAccessor;
use Ruwork\UploadBundle\Path\PathLocatorInterface;
use Ruwork\UploadBundle\Source\ResolvedSource;
use Ruwork\UploadBundle\Source\SourceResolverInterface;

final class UploadManager implements UploadManagerInterface
{
    private $sourceResolver;
    private $accessor;
    private $pathLocator;

    /**
     * @var ResolvedSource[]
     */
    private $resolvedSources;

    public function __construct(
        SourceResolverInterface $sourceResolver,
        UploadAccessor $accessor,
        PathLocatorInterface $pathLocator
    ) {
        $this->sourceResolver = $sourceResolver;
        $this->accessor = $accessor;
        $this->pathLocator = $pathLocator;
        $this->resolvedSources = new \SplObjectStorage();
    }

    /**
     * {@inheritdoc}
     */
    public function register($object, $source): void
    {
        $resolvedSource = $this->sourceResolver->resolve($source);
        $this->accessor->setPath($object, $resolvedSource->getPath());
        $this->accessor->setAttributes($object, $resolvedSource->getAttributes());
        $this->resolvedSources->attach($object, $resolvedSource);
    }

    /**
     * {@inheritdoc}
     */
    public function getResolvedSource($object): ResolvedSource
    {
        if (!$this->resolvedSources->contains($object)) {
            throw new NotRegisteredException('Object is not registered.');
        }

        return $this->resolvedSources[$object];
    }

    /**
     * {@inheritdoc}
     */
    public function save($object): void
    {
        $this->getResolvedSource($object)->save();
        $this->detach($object);
    }

    /**
     * {@inheritdoc}
     */
    public function detach($object): void
    {
        $this->resolvedSources->detach($object);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->resolvedSources->removeAll($this->resolvedSources);
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($object): string
    {
        return $this->accessor->getPath($object);
    }

    /**
     * {@inheritdoc}
     */
    public function locate($object): string
    {
        $path = $this->getPath($object);

        return $this->pathLocator->locatePath($path);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($object): void
    {
        @\unlink($this->locate($object));
    }
}
