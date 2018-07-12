<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Validator;

use Ruwork\UploadBundle\Exception\EmptyPathException;
use Ruwork\UploadBundle\Exception\NotRegisteredException;
use Ruwork\UploadBundle\Form\Type\UploadType;
use Ruwork\UploadBundle\Manager\UploadManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class AssertUploadValidator extends ConstraintValidator
{
    private $manager;

    public function __construct(UploadManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AssertUpload) {
            throw new UnexpectedTypeException($constraint, AssertUpload::class);
        }

        if (null === $value) {
            return;
        }

        if (!\is_object($value)) {
            throw new UnexpectedTypeException($value, 'object');
        }

        $file = null;

        try {
            $file = $this->manager->getResolvedSource($value)->getTmpPath();
        } catch (NotRegisteredException $exception) {
            try {
                $file = $this->manager->locate($value);
            } catch (EmptyPathException $exception) {
            }
        }

        $this->context
            ->getValidator()
            ->inContext($this->context)
            ->atPath(UploadType::FILE)
            ->validate($file, $constraint->constraints);
    }
}
