<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Composite;

@\trigger_error(\sprintf('Class %s is deprecated since 0.12 and will be removed in 0.13. Use ruwork/validator-constraints package instead.', Condition::class), E_USER_DEPRECATED);

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
