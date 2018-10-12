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

@\trigger_error(\sprintf('Class %s is deprecated since 0.12 and will be removed in 0.13. Use ruwork/validator-constraints package instead.', AliasValidator::class), E_USER_DEPRECATED);

class AliasValidator extends ConstraintValidator
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
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

        if (null !== $object && null !== $this->managerRegistry->getManagerForClass(\get_class($object))) {
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
