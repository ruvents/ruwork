<?php

declare(strict_types=1);

namespace Ruwork\ValidatorConstraints\Constraints;

use Symfony\Component\Validator\Constraints\Composite;

/**
 * @Annotation()
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
final class AtPath extends Composite
{
    public const EXCEPTION = 'EXCEPTION';
    public const IGNORE = 'IGNORE';
    public const NULL = 'NULL';

    public $path;
    public $constraints;

    /**
     * @Enum({"NULL", "IGNORE", "EXCEPTION"})
     */
    public $onAccessException = self::IGNORE;

    /**
     * @Enum({"NULL", "IGNORE", "EXCEPTION"})
     */
    public $onUnexpectedTypeException = self::IGNORE;

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return [
            'path',
            'constraints',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'path';
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return AtPathValidator::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCompositeOption()
    {
        return 'constraints';
    }
}
