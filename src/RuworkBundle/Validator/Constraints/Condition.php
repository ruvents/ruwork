<?php

namespace Ruvents\RuworkBundle\Validator\Constraints;

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

    /**
     * @var array
     */
    public $true = [];

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
        return 'true';
    }
}
