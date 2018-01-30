<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Serializer;

use Ruwork\UploadBundle\Entity\AbstractUpload;
use Ruwork\UploadBundle\UploadManager;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UploadNormalizer implements NormalizerInterface
{
    const TYPE = 'ruwork_upload_type';
    const URL = 'url';
    const PATHNAME = 'pathname';

    private $manager;

    public function __construct(UploadManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     *
     * @param AbstractUpload $upload
     */
    public function normalize($upload, $format = null, array $context = [])
    {
        $type = $context[self::TYPE] ?? self::URL;

        if (self::URL === $type) {
            return $this->manager->getUrl($upload);
        }

        return $this->manager->getPathname($upload);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof AbstractUpload;
    }
}
