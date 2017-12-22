<?php

namespace Ruwork\DoctrineFilterBundle;

use Doctrine\ORM\QueryBuilder;
use Psr\Container\ContainerInterface;
use Ruwork\DoctrineFilterBundle\Type\FilterTypeInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterManager
{
    /**
     * @var ContainerInterface|ServiceLocator[]
     */
    public $types;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(ContainerInterface $types, FormFactoryInterface $formFactory, RequestStack $requestStack)
    {
        $this->formFactory = $formFactory;
        $this->types = $types;
        $this->requestStack = $requestStack;
    }

    public function apply(string $filterType, QueryBuilder $queryBuilder, array $options = []): FilterResult
    {
        if (null === $request = $this->requestStack->getCurrentRequest()) {
            throw new \LogicException();
        }

        if (!$this->types->has($filterType)) {
            throw new \InvalidArgumentException();
        }

        /** @var FilterTypeInterface $type */
        $type = $this->types->get($filterType);

        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $options = $resolver->resolve($options);

        $form = $type->createForm($this->formFactory, $options)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->all() as $name => $child) {
                $method = $name.'Filter';

                if (!method_exists($type, $method) || $child->isEmpty()) {
                    continue;
                }

                call_user_func([$type, $method], $child->getData(), $queryBuilder, $options);
            }
        }

        return new FilterResult($form, $queryBuilder, $options);
    }
}
