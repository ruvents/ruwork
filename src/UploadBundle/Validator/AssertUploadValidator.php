<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Validator;

use Ruwork\UploadBundle\Exception\EmptyPathException;
use Ruwork\UploadBundle\Form\Type\UploadType;
use Ruwork\UploadBundle\Locator\UploadLocatorInterface;
use Ruwork\UploadBundle\Manager\UploadManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class AssertUploadValidator extends ConstraintValidator
{
    private $manager;
    private $locator;

    public function __construct(UploadManagerInterface $manager, UploadLocatorInterface $locator)
    {
        $this->manager = $manager;
        $this->locator = $locator;
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

        $file = null;

        if ($this->manager->isRegistered($value)) {
            $file = $this->manager->getSource($value);
        } else {
            try {
                $file = $this->locator->locateUpload($value);
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
