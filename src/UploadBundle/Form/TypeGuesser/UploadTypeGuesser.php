<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\TypeGuesser;

use Doctrine\Common\Persistence\ManagerRegistry;
use Ruwork\UploadBundle\Entity\AbstractUpload;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Component\Form\Guess;

class UploadTypeGuesser implements FormTypeGuesserInterface
{
    private $doctrine;

    private $type;

    public function __construct(ManagerRegistry $doctrine, string $type)
    {
        $this->doctrine = $doctrine;
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function guessType($class, $property)
    {
        $manager = $this->doctrine->getManagerForClass($class);

        if (null === $manager) {
            return null;
        }

        $metadata = $manager->getClassMetadata($class);

        if (!$metadata->hasAssociation($property)) {
            return null;
        }

        $targetClass = $metadata->getAssociationTargetClass($property);

        if (!is_subclass_of($targetClass, AbstractUpload::class)) {
            return null;
        }

        if ($metadata->isSingleValuedAssociation($property)) {
            return new Guess\TypeGuess($this->type, [], Guess\TypeGuess::VERY_HIGH_CONFIDENCE);
        }

        return new Guess\TypeGuess(CollectionType::class, [
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => $this->type,
        ], Guess\TypeGuess::VERY_HIGH_CONFIDENCE);
    }

    /**
     * {@inheritdoc}
     */
    public function guessRequired($class, $property)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function guessMaxLength($class, $property)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function guessPattern($class, $property)
    {
        return null;
    }
}
