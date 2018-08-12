<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Source\Handler;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UploadedFileSourceHandler implements SourceHandlerInterface, AttributesProviderInterface
{
    public const CLIENT_MIME_TYPE = 'client_mime_type';

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
    public function write($source, string $target): void
    {
        $source->move(\dirname($target), \basename($target));
    }

    /**
     * {@inheritdoc}
     *
     * @param UploadedFile $source
     */
    public function getAttributes($source): array
    {
        return [
            self::CLIENT_MIME_TYPE => $source->getClientMimeType(),
            self::CLIENT_NAME => $source->getClientOriginalName(),
            self::TMP_PATH => $source->getPathname(),
        ];
    }
}
