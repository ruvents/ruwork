<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Ruwork\DoctrineBehaviorsBundle\Metadata\MetadataFactoryInterface;
use Ruwork\DoctrineBehaviorsBundle\Multilingual\MultilingualInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MultilingualListener
{
    use DefaultMappingTrait;

    private $factory;
    private $requestStack;
    private $defaultLocale;

    /**
     * @var \SplObjectStorage|MultilingualInterface[]
     */
    private $multilinguals;

    public function __construct(MetadataFactoryInterface $factory, RequestStack $requestStack, string $defaultLocale)
    {
        $this->factory = $factory;
        $this->requestStack = $requestStack;
        $this->defaultLocale = $defaultLocale;
        $this->multilinguals = new \SplObjectStorage();
    }

    public function onKernelRequest(): void
    {
        $currentLocale = $this->getCurrentLocale();

        foreach ($this->multilinguals as $multilingual) {
            $multilingual->setCurrentLocale($currentLocale);
        }
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $class = get_class($entity);
        $metadata = $args->getEntityManager()->getClassMetadata($class);
        $multilinguals = $this->factory->getMetadata($class)->getMultilinguals();

        foreach ($multilinguals as $property => $multilingual) {
            $value = $metadata->getFieldValue($entity, $property);

            if (!$value instanceof MultilingualInterface) {
                throw new \UnexpectedValueException(sprintf('Value of %s.%s@Multilingual must be an instance of "%s", "%s" given.', $class, $property, MultilingualInterface::class, is_object($value) ? get_class($value) : gettype($value)));
            }

            if (!$this->multilinguals->contains($value)) {
                $value->setCurrentLocale($this->getCurrentLocale());
                $this->multilinguals->attach($value);
            }
        }
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $this->prePersist($args);
    }

    /**
     * {@inheritdoc}
     */
    protected function getMappedProperties(string $class): iterable
    {
        return array_keys($this->factory->getMetadata($class)->getMultilinguals());
    }

    private function getCurrentLocale(): string
    {
        if (null !== $request = $this->requestStack->getCurrentRequest()) {
            return $request->getLocale();
        }

        return $this->defaultLocale;
    }
}
