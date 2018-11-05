<?php

declare(strict_types=1);

namespace Ruwork\Wizard\FormFactory;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface as SymfonyFormFactoryInterface;

final class SymfonyFormFactory implements FormFactoryInterface
{
    private $factory;
    private $rootName;
    private $rootType;
    private $rootOptions;
    private $name;
    private $type;
    private $options;

    public function __construct(
        SymfonyFormFactoryInterface $factory,
        string $rootName,
        string $rootType,
        array $rootOptions,
        string $name,
        string $type,
        array $options = []
    ) {
        $this->factory = $factory;
        $this->rootName = $rootName;
        $this->rootType = $rootType;
        $this->rootOptions = $rootOptions;
        $this->name = $name;
        $this->type = $type;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function create($data, callable $handler)
    {
        $stepName = $this->name;
        $stepBuilder = $this->factory->createNamedBuilder($stepName, $this->type, $data, $this->options);

        return $this->factory
            ->createNamedBuilder($this->rootName, $this->rootType, null, $this->rootOptions)
            ->add($stepBuilder)
            ->addEventListener(FormEvents::POST_SUBMIT,
                static function (FormEvent $event) use ($stepName, $handler): void {
                    $handler($event->getForm()->get($stepName)->getData());
                }
            )
            ->getForm();
    }
}
