<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\ImageValidator;

/**
 * @Annotation()
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class RasterImage extends Image
{
    public $mimeTypes = [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
    ];

    public $mimeTypesMessage = 'raster_image_invalid_mime_type';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return ImageValidator::class;
    }
}
