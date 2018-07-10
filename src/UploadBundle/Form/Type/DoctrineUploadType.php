<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Ruwork\UploadBundle\Doctrine\Repository\UploadFinderInterface;
use Ruwork\UploadBundle\Metadata\MetadataFactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DoctrineUploadType extends AbstractType
{
    private $doctrine;
    private $metadataFactory;

    public function __construct(ManagerRegistry $doctrine, MetadataFactoryInterface $metadataFactory)
    {
        $this->doctrine = $doctrine;
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'finder' => function (Options $options): callable {
                    $class = $options['class'];

                    return function (string $path) use ($class) {
                        $repository = $this
                            ->getManager($class)
                            ->getRepository($class);

                        if ($repository instanceof UploadFinderInterface) {
                            return $repository->findOneByPath($path);
                        }

                        $pathProperty = $this->metadataFactory
                            ->getMetadata($class)
                            ->getPathProperty();

                        return $repository->findOneBy([$pathProperty => $path]);
                    };
                },
                'on_terminate' => function (Options $options): callable {
                    $class = $options['class'];

                    return function ($upload) use ($class): void {
                        $manager = $this->getManager($class);

                        $manager->clear();
                        $manager->persist($upload);
                        $manager->flush();
                    };
                },
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return UploadType::class;
    }

    private function getManager(string $class): ObjectManager
    {
        $manager = $this->doctrine->getManagerForClass($class);

        if (null === $manager) {
            throw new \InvalidArgumentException(sprintf('No doctrine manager found for class "%s".', $class));
        }

        return $manager;
    }
}
