<?php

declare(strict_types=1);

namespace Ruwork\FilterBundle;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

final class Filter implements FilterInterface
{
    private const METHOD_SUFFIX = 'Filter';

    private $formFactory;
    private $type;
    private $options;

    public function __construct(
        FormFactoryInterface $formFactory,
        FilterTypeInterface $type,
        array $options
    ) {
        $this->formFactory = $formFactory;
        $this->type = $type;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function apply($object, Request $request): FilterResultInterface
    {
        $form = $this->type
            ->createForm($this->formFactory, $this->options)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form as $name => $child) {
                $method = $name.self::METHOD_SUFFIX;

                if (method_exists($this->type, $method)) {
                    ($this->type->$method)($child, $object, $this->options);
                }
            }
        }

        return new FilterResult($object, $form, $this->options);
    }
}
