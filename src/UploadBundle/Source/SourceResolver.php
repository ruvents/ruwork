<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Source;

use Ruwork\UploadBundle\Path\PathGeneratorInterface;
use Ruwork\UploadBundle\Path\PathLocatorInterface;
use Ruwork\UploadBundle\Source\Handler\AttributesProviderInterface;
use Ruwork\UploadBundle\Source\Handler\SourceHandlerInterface;
use Ruwork\UploadBundle\TmpPath\TmpPathGeneratorInterface;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesserInterface;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesserInterface;

final class SourceResolver implements SourceResolverInterface
{
    private $handlers;
    private $tmpPathGenerator;
    private $pathGenerator;
    private $pathLocator;
    private $mimeTypeGuesser;
    private $extensionGuesser;

    /**
     * @param SourceHandlerInterface[] $handlers
     */
    public function __construct(
        iterable $handlers,
        TmpPathGeneratorInterface $tmpPathGenerator,
        PathGeneratorInterface $pathGenerator,
        PathLocatorInterface $pathLocator,
        ?MimeTypeGuesserInterface $mimeTypeGuesser = null,
        ?ExtensionGuesserInterface $extensionGuesser = null
    ) {
        $this->handlers = $handlers;
        $this->tmpPathGenerator = $tmpPathGenerator;
        $this->pathGenerator = $pathGenerator;
        $this->pathLocator = $pathLocator;
        $this->mimeTypeGuesser = $mimeTypeGuesser ?? MimeTypeGuesser::getInstance();
        $this->extensionGuesser = $extensionGuesser ?? ExtensionGuesser::getInstance();
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($source): ResolvedSourceInterface
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($source)) {
                return $this->createResolvedSource($source, $handler);
            }
        }

        throw new \RuntimeException('No handler.');
    }

    private function createResolvedSource($source, SourceHandlerInterface $handler): ResolvedSource
    {
        $attributes = [];
        $saveFromSource = true;

        if ($handler instanceof AttributesProviderInterface) {
            $attributes = $handler->getAttributes($source);
        }

        $tmpPath = $attributes[AttributesProviderInterface::TMP_PATH] ?? '';

        if (!is_file($tmpPath)) {
            $tmpPath = $this->tmpPathGenerator->generateTmpPath();
            $handler->write($source, $tmpPath);
            $saveFromSource = false;
        }

        $mimeType = $this->mimeTypeGuesser->guess($tmpPath);
        $extension = $this->extensionGuesser->guess($mimeType);
        $path = $this->pathGenerator->generatePath($extension);
        $absolutePath = $this->pathLocator->locatePath($path);

        return new ResolvedSource($source, $handler, $attributes, $tmpPath, $path, $absolutePath, $saveFromSource);
    }
}
