<?php

declare(strict_types=1);

namespace Ruwork\ValidatorConstraints\Constraints;

use Symfony\Component\Validator\Constraints\Composite;

/**
 * @Annotation()
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
final class Sequence extends Composite
{
    public $constraints;

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
    public function validatedBy()
    {
        return SequenceValidator::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCompositeOption()
    {
        return 'constraints';
    }
}
