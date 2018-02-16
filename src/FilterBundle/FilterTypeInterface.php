<?php

declare(strict_types=1);

namespace Ruwork\FilterBundle;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface FilterTypeInterface
{
    public function createForm(FormFactoryInterface $factory, array $options): FormInterface;

    public function configureOptions(OptionsResolver $resolver): void;
}
