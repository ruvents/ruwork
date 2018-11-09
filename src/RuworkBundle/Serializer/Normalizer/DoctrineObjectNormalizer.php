<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Serializer\Normalizer;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

final class DoctrineObjectNormalizer implements ContextAwareNormalizerInterface, ContextAwareDenormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public const ENABLED = 'ruwork_bundle.doctrine_object.enabled';

    private $registry;
    private $managers = [];

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        if (!($context[self::ENABLED] ?? false)) {
            return false;
        }

        if (!\is_object($data)) {
            return false;
        }

        return null !== $manager = $this->getManager(\get_class($data));
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $id = $this->registry
            ->getManagerForClass($class = \get_class($object))
            ->getClassMetadata($class)
            ->getIdentifierValues($object);

        return $this->normalizer->normalize($id, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        if (!($context[self::ENABLED] ?? false)) {
            return false;
        }

        if (!class_exists($type)) {
            return false;
        }

        return null !== $manager = $this->getManager($type);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return $this->registry
            ->getManagerForClass($class)
            ->find($class, $data);
    }

    private function getManager(string $class): ?ObjectManager
    {
        if (array_key_exists($class, $this->managers)) {
            return $this->managers[$class];
        }

        return $this->managers[$class] = $this->registry->getManagerForClass($class);
    }
}
