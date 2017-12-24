<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\Form\EventListener;

use Ruwork\AdminBundle\Config\Model\Field\FormFieldConfig;
use Ruwork\AdminBundle\Form\Type\GroupType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AddFieldsListener implements EventSubscriberInterface
{
    private $authChecker;

    private $fields;

    /**
     * @param FormFieldConfig[] $fields
     */
    public function __construct(AuthorizationCheckerInterface $authChecker, array $fields)
    {
        $this->authChecker = $authChecker;
        $this->fields = $fields;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }

    public function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $entity = $event->getData();
        $class = $event->getForm()->getConfig()->getDataClass();
        $factory = $form->getConfig()->getFormFactory();

        foreach ($this->fields as $field) {
            if ($field->requiresGranted && !$this->authChecker->isGranted($field->requiresGranted, $entity)) {
                continue;
            }

            $options = $field->options + ['auto_initialize' => false];

            if (null === $field->type) {
                $childBuilder = $factory->createBuilderForProperty($class, $field->name, null, $options);
            } else {
                $childBuilder = $factory->createNamedBuilder($field->name, $field->type, null, $options);
            }

            if ($childBuilder->getAttribute('ruwork_admin.is_group', false)) {
                $form->add($group = $childBuilder->getForm());

                continue;
            }

            if (!isset($group)) {
                $group = $form->add('__group0', GroupType::class)->get('__group0');
            }

            $group->add($childBuilder->getForm());
        }
    }
}
