<?php

declare(strict_types=1);

namespace Ruwork\Wizard\FormFactory;

final class NullFormFactory implements FormFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create($data, callable $handler)
    {
        return null;
    }
}
