<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Form;

use Symfony\Component\Form\FormInterface;

interface FormErrorsMapperInterface
{
    /**
     * @param callable|iterable $map
     */
    public function save(string $id, $object, $map): void;

    public function apply(string $id, FormInterface $form): void;
}
