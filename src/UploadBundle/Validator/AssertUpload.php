<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Validator;

use Symfony\Component\Validator\Constraints\Composite;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
final class AssertUpload extends Composite
{
    public $constraints;

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return AssertUploadValidator::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return [
            'constraints',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'constraints';
    }

    /**
     * {@inheritdoc}
     */
    protected function getCompositeOption()
    {
        return 'constraints';
    }
}
