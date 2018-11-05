<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\Type;

use Ruwork\Wizard\Step\Builder\StepConfiguratorInterface;
use Ruwork\Wizard\Validator\SymfonyValidator;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SymfonyValidatorStepType extends AbstractStepType
{
    private $validator;

    public function __construct(?ValidatorInterface $validator = null)
    {
        $this->validator = $validator ?? Validation::createValidator();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'validation_constraints' => null,
                'validation_groups' => null,
            ])
            ->setAllowedTypes('validation_constraints', [
                'null',
                Constraint::class,
                Constraint::class.'[]',
            ])
            ->setAllowedTypes('validation_groups', [
                'null',
                'string',
                'string[]',
                GroupSequence::class,
                GroupSequence::class.'[]',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureStep(StepConfiguratorInterface $configurator, array $options): void
    {
        $configurator->setValidator(new SymfonyValidator(
            $this->validator,
            $options['validation_constraints'],
            $options['validation_groups']
        ));
    }
}
