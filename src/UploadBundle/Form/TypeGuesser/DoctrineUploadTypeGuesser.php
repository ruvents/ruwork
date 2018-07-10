<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\TypeGuesser;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Ruwork\UploadBundle\Exception\NotMappedException;
use Ruwork\UploadBundle\Form\Type\DoctrineUploadType;
use Ruwork\UploadBundle\Metadata\MetadataFactoryInterface;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmTypeGuesser;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;

final class DoctrineUploadTypeGuesser extends DoctrineOrmTypeGuesser
{
    private $metadataFactory;

    public function __construct(ManagerRegistry $doctrine, MetadataFactoryInterface $metadataFactory)
    {
        parent::__construct($doctrine);
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function guessType($class, $property)
    {
        if (!$result = $this->getMetadata($class)) {
            return null;
        }

        /** @var ClassMetadata $metadata */
        $metadata = $result[0];

        if (!$metadata->hasAssociation($property) || $metadata->isCollectionValuedAssociation($property)) {
            return null;
        }

        $uploadClass = $metadata->getAssociationTargetClass($property);

        try {
            $this->metadataFactory->getMetadata($uploadClass);
        } catch (NotMappedException $exception) {
            return null;
        }

        return new TypeGuess(
            DoctrineUploadType::class,
            ['class' => $uploadClass],
            Guess::HIGH_CONFIDENCE
        );
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
