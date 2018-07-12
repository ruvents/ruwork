<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Validator\Constraints;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AliasValidator extends ConstraintValidator
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Alias) {
            throw new UnexpectedTypeException($constraint, Alias::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $context = $this->context;

        $context->getValidator()
            ->inContext($context)
            ->atPath($context->getPropertyPath())
            ->validate($value, [
                new Length([
                    'max' => $constraint->maxLength,
                    'maxMessage' => $constraint->maxLengthMessage,
                ]),
                new Regex([
                    'htmlPattern' => $constraint->htmlPattern,
                    'message' => $constraint->regexMessage,
                    'pattern' => $constraint->pattern,
                ]),
            ]);

        $object = $context->getObject();

        if (null !== $object && null !== $this->registry->getManagerForClass(\get_class($object))) {
            $context->getValidator()
                ->inContext($context)
                ->atPath($context->getPropertyPath())
                ->validate($object, [
                    new UniqueEntity([
                        'entityClass' => $constraint->entityClass,
                        'fields' => $context->getPropertyName(),
                        'ignoreNull' => false,
                        'repositoryMethod' => $constraint->repositoryMethod,
                    ]),
                ]);
        }
    }
}
