<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Source\Handler;

use Ruwork\UploadBundle\PathGenerator\PathGeneratorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UploadedFileHandler implements SourceHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($source): bool
    {
        return $source instanceof UploadedFile;
    }

    /**
     * {@inheritdoc}
     *
     * @param UploadedFile $source
     */
    public function getAttributes($source): array
    {
        return [
            'client_name' => $source->getClientOriginalName(),
            'client_mime_type' => $source->getClientMimeType(),
            PathGeneratorInterface::EXTENSION => $source->guessExtension(),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @param UploadedFile $source
     */
    public function write($source, array $attributes, string $target): void
    {
        $source->move(dirname($target), basename($target));
    }
}
