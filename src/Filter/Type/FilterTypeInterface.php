<?php

declare(strict_types=1);

namespace Ruwork\Filter\Type;

use Ruwork\Filter\Builder\FilterBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface FilterTypeInterface
{
    public function build(FilterBuilderInterface $builder, array $options): void;

    public function configureOptions(OptionsResolver $resolver): void;
}
