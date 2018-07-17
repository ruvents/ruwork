<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Composite;

/**
 * @Annotation()
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Condition extends Composite
{
    /**
     * @var string
     */
    public $expression;

    public $constraints = [];

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'expression';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return [
            'expression',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getCompositeOption()
    {
        return 'constraints';
    }
}
