<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Manager;

use Ruwork\UploadBundle\Exception\NotMappedException;
use Ruwork\UploadBundle\Locator\UploadLocatorInterface;
use Ruwork\UploadBundle\Metadata\MetadataFactoryInterface;
use Ruwork\UploadBundle\Metadata\UploadAccessor;
use Ruwork\UploadBundle\PathGenerator\PathGeneratorInterface;
use Ruwork\UploadBundle\Source\ResolvedSource;
use Ruwork\UploadBundle\Source\SourceResolverInterface;

final class UploadManager implements UploadManagerInterface
{
    private $metadataFactory;
    private $sourceResolver;
    private $pathGenerator;
    private $accessor;
    private $locator;

    /**
     * @var bool[]
     */
    private $uploadClasses = [];

    /**
     * @var ResolvedSource[]
     */
    private $resolvedSources;

    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        SourceResolverInterface $sourceResolver,
        PathGeneratorInterface $pathGenerator,
        UploadAccessor $accessor,
        UploadLocatorInterface $locator
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->sourceResolver = $sourceResolver;
        $this->pathGenerator = $pathGenerator;
        $this->accessor = $accessor;
        $this->locator = $locator;
        $this->resolvedSources = new \SplObjectStorage();
    }

    /**
     * {@inheritdoc}
     */
    public function isUpload($object): bool
    {
        $class = is_string($object) ? $object : get_class($object);

        if (isset($this->uploadClasses[$class])) {
            return $this->uploadClasses[$class];
        }

        try {
            $this->metadataFactory->getMetadata($class);

            return $this->uploadClasses[$class] = true;
        } catch (NotMappedException $exception) {
            return $this->uploadClasses[$class] = false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isRegistered($object): bool
    {
        return $this->resolvedSources->contains($object);
    }

    /**
     * {@inheritdoc}
     */
    public function register($object, $source): void
    {
        $resolvedSource = $this->sourceResolver->resolve($source);
        $attributes = $resolvedSource->getAttributes();
        $path = $this->pathGenerator->generate($attributes);
        $this->accessor->setPath($object, $path);
        $this->accessor->setAttributes($object, $attributes);
        $this->resolvedSources->attach($object, $resolvedSource);
    }

    /**
     * {@inheritdoc}
     */
    public function getSource($object)
    {
        if (!$this->isRegistered($object)) {
            throw new \RuntimeException('Object is not registered.');
        }

        return $this->resolvedSources[$object]->getSource();
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
    public function save($object): void
    {
        if (!$this->isRegistered($object)) {
            throw new \RuntimeException('Object is not registered.');
        }

        $this->doSave($object);
    }

    /**
     * {@inheritdoc}
     */
    public function saveAll(): void
    {
        foreach ($this->resolvedSources as $object) {
            $this->doSave($object);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($object): void
    {
        @unlink($this->locator->locateUpload($object));
        $this->detach($object);
    }

    private function doSave($object): void
    {
        $target = $this->locator->locateUpload($object);
        $this->resolvedSources[$object]->write($target);
        $this->detach($object);
    }

}
