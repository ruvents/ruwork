<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\Type;

use Ruwork\Wizard\FormFactory\SymfonyFormFactory;
use Ruwork\Wizard\Step\Builder\StepConfiguratorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SymfonyFormStepType extends AbstractStepType
{
    private $formFactory;

    public function __construct(?FormFactoryInterface $formFactory = null)
    {
        $this->formFactory = $formFactory ?? Forms::createFormFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('form_type')
            ->setDefaults([
                'root_form_name' => 'form',
                'root_form_type' => FormType::class,
                'root_form_options' => [],
                'form_name' => 'step',
                'form_options' => [],
            ])
            ->setAllowedTypes('root_form_name', 'string')
            ->setAllowedTypes('root_form_type', 'string')
            ->setAllowedTypes('root_form_options', 'array')
            ->setAllowedTypes('form_name', 'string')
            ->setAllowedTypes('form_type', 'string')
            ->setAllowedTypes('form_options', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function configureStep(StepConfiguratorInterface $configurator, array $options): void
    {
        $configurator->setFormFactory(new SymfonyFormFactory(
            $this->formFactory,
            $options['root_form_name'],
            $options['root_form_type'],
            $options['root_form_options'],
            $options['form_name'],
            $options['form_type'],
            $options['form_options']
        ));
    }
}
