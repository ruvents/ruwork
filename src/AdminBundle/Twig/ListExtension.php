<?php
declare(strict_types=1);

namespace Ruwork\AdminBundle\Twig;

use Doctrine\Common\Persistence\ManagerRegistry;
use Ruwork\AdminBundle\Config\Model\Config;
use Ruwork\AdminBundle\ListField\TypeContextProcessor\TypeContextProcessorInterface;
use Ruwork\AdminBundle\ListField\TypeGuesser\TypeGuesserInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ListExtension extends AbstractExtension
{
    private $config;

    private $guessers;

    private $processors;

    private $typesTemplate;

    private $registry;

    private $accessor;

    private $contexts = [];

    /**
     * @param iterable|TypeGuesserInterface[]            $guessers
     * @param iterable|TypeContextProcessorInterface[][] $processors
     */
    public function __construct(
        Config $config,
        ManagerRegistry $registry,
        PropertyAccessorInterface $accessor,
        iterable $guessers,
        iterable $processors,
        string $typesTemplate
    ) {
        $this->config = $config;
        $this->guessers = $guessers;
        $this->processors = $processors;
        $this->typesTemplate = $typesTemplate;
        $this->registry = $registry;
        $this->accessor = $accessor;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('ruwork_admin_render_list_field', [$this, 'renderListField'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    public function renderListField(Environment $twig, string $entityName, $entity, string $propertyPath = null, string $type = null): string
    {
        $context = $this->getContext($entityName, $propertyPath, $type);

        $context['entity'] = $entity;
        $context['value'] = $propertyPath ? $this->accessor->getValue($entity, $propertyPath) : null;
        $context['value_php_type'] = gettype($context['value']);

        return $twig->load($this->typesTemplate)->renderBlock($context['type'], $context);
    }

    private function getContext(string $entityName, string $propertyPath = null, string $type = null)
    {
        if (null === $propertyPath && null === $type) {
            throw new \LogicException('$propertyPath and $type cannot be null at the same time.');
        }

        $lazyKey = $entityName.':'.$propertyPath.':'.$type;

        if (!isset($this->contexts[$lazyKey])) {
            $this->contexts[$lazyKey] = $this->createContext($entityName, $propertyPath, $type);
        }

        return $this->contexts[$lazyKey];
    }

    private function createContext(string $entityName, string $propertyPath = null, string $type = null): array
    {
        $entityConfig = $this->config->entities[$entityName];
        $entityClass = $entityConfig->class;

        if (null === $type) {
            $type = $this->guessType($entityClass, $propertyPath);
        }

        $context = [
            'entity_class' => $entityClass,
            'entity_config' => $entityConfig,
            'entity_has_to_string' => method_exists($entityClass, '__toString'),
            'entity_name' => $entityName,
            'entity_id_property' => $this->registry
                ->getManagerForClass($entityClass)
                ->getClassMetadata($entityClass)
                ->getIdentifierFieldNames()[0],
            'type' => $type,
        ];

        foreach ($this->processors[$type] ?? [] as $processor) {
            $processor->process($entityClass, $propertyPath, $context);
        }

        return $context;
    }

    private function guessType(string $class, string $propertyPath): string
    {
        foreach ($this->guessers as $guesser) {
            if (null !== $type = $guesser->guess($class, $propertyPath)) {
                return $type;
            }
        }

        return 'default';
    }
}
