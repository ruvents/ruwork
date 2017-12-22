<?php
declare(strict_types=1);

namespace Ruwork\UploadBundle\Validator;

use Ruwork\UploadBundle\Entity\AbstractUpload;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UploadFileValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UploadFile) {
            throw new UnexpectedTypeException($constraint, UploadFile::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof AbstractUpload) {
            throw new UnexpectedTypeException($value, AbstractUpload::class);
        }

        $file = $value->getUploadedFile() ?? $value->getPath();

        $this->context
            ->getValidator()
            ->inContext($this->context)
            ->atPath('uploadedFile')
            ->validate($file, $constraint->constraints);
    }
}
