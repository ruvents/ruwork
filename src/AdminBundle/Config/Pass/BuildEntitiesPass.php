<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\Config\Pass;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Ruwork\AdminBundle\Config\Model\Action\DeleteActionConfig;
use Ruwork\AdminBundle\Config\Model\Action\FormActionConfig;
use Ruwork\AdminBundle\Config\Model\Action\ListActionConfig;
use Ruwork\AdminBundle\Config\Model\Config;
use Ruwork\AdminBundle\Config\Model\EntityConfig;
use Ruwork\AdminBundle\Config\Model\Field\FormFieldConfig;
use Ruwork\AdminBundle\Config\Model\Field\ListFieldConfig;

class BuildEntitiesPass implements PassInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Config $config, array $data): void
    {
        $entities = [];

        foreach ($data['entities'] as $name => $entityData) {
            $entities[$name] = $this->buildEntity($name, $entityData);
        }

        $config->entities = $entities;
    }

    private function buildEntity(string $name, array $data): EntityConfig
    {
        $this->checkEntity($class = $data['class']);

        $entity = new EntityConfig();
        $entity->name = $name;
        $entity->class = $class;
        $entity->requiresGranted = $data['requires_granted'];
        $entity->list = $this->buildListAction($data['list']);

        if (empty($data['create']['fields'])) {
            $data['create']['fields'] = $data['edit']['fields'];
        } elseif (empty($data['edit']['fields'])) {
            $data['edit']['fields'] = $data['create']['fields'];
        }

        $entity->create = $this->buildFormAction($data['create']);
        $entity->edit = $this->buildFormAction($data['edit']);
        $entity->delete = $this->buildDeleteAction($data['delete']);

        return $entity;
    }

    private function buildListAction(array $data): ListActionConfig
    {
        $action = new ListActionConfig();

        $action->enabled = $data['enabled'];
        $action->perPage = $data['per_page'];
        $action->title = $data['title'];
        $action->requiresGranted = $data['requires_granted'];
        $action->fields = array_map([$this, 'buildListField'], $data['fields']);

        return $action;
    }

    private function buildListField(array $data): ListFieldConfig
    {
        $field = new ListFieldConfig();

        $field->propertyPath = $data['property_path'];
        $field->type = $data['type'];
        $field->title = $data['title'];

        return $field;
    }

    private function buildFormAction(array $data): FormActionConfig
    {
        $action = new FormActionConfig();

        $action->enabled = $data['enabled'];
        $action->title = $data['title'];
        $action->requiresGranted = $data['requires_granted'];
        $action->type = $data['type'];
        $action->options = $data['options'];
        $action->theme = $data['theme'];
        $action->fields = array_map([$this, 'buildFormField'], $data['fields']);

        return $action;
    }

    private function buildFormField(array $data): FormFieldConfig
    {
        $field = new FormFieldConfig();

        $field->name = $data['name'];
        $field->type = $data['type'];
        $field->requiresGranted = $data['requires_granted'];
        $field->options = $data['options'];

        return $field;
    }

    private function buildDeleteAction(array $data): DeleteActionConfig
    {
        $action = new DeleteActionConfig();

        $action->enabled = $data['enabled'];
        $action->requiresGranted = $data['requires_granted'];

        return $action;
    }

    private function checkEntity(string $class): void
    {
        $manager = $this->registry->getManagerForClass($class);

        if (null === $manager) {
            throw new \InvalidArgumentException(sprintf('No manager was found for class "%s".', $class));
        }

        if (!$manager instanceof EntityManagerInterface) {
            throw new \InvalidArgumentException(sprintf('Manager for class %s is not an instance of %s.', $class, EntityManagerInterface::class));
        }

        $metadata = $manager->getClassMetadata($class);

        if ($metadata->isMappedSuperclass) {
            throw new \InvalidArgumentException(sprintf('Class %s is not an entity but a mapped superclass.', $class));
        }

        if ($metadata->isEmbeddedClass) {
            throw new \InvalidArgumentException(sprintf('Class %s is not an entity but an embeddable class.', $class));
        }

        if ($metadata->isIdentifierComposite) {
            throw new \InvalidArgumentException(sprintf('Entity %s has a composite identifier which is currently not supported.', $class));
        }
    }
}
