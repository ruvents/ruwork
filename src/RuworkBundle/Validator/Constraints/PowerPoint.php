<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\FileValidator;

@\trigger_error(\sprintf('Class %s is deprecated since 0.12 and will be removed in 0.13. Use ruwork/validator-constraints package instead.', PowerPoint::class), E_USER_DEPRECATED);

/**
 * @Annotation()
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class PowerPoint extends File
{
    public $mimeTypes = [
        'application/mspowerpoint',
        'application/vnd.ms-powerpoint',
        'application/vnd.ms-powerpoint.addin.macroEnabled.12',
        'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
        'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
        'application/vnd.ms-powerpoint.template.macroEnabled.12',
    ];

    public $mimeTypesMessage = 'power_point_invalid_mime_type';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return FileValidator::class;
    }
}
