<?php

declare(strict_types=1);

namespace Ruwork\ValidatorConstraints\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation()
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
final class EmailHost extends Constraint
{
    public $types = ['A', 'AAAA', 'MX'];
    public $message = 'Host {{ value }} was not found. Checked {{ types }} resource records.';

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'types';
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return EmailHostValidator::class;
    }
}
