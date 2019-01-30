<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Ruwork\AdminBundle\Form\EventListener\AddFieldsListener;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class AbstractController extends SymfonyAbstractController
{
    protected function isClicked(FormInterface $form, string $name): bool
    {
        if (!$form->has($name)) {
            return false;
        }

        $button = $form->get($name);

        if (!$button instanceof ClickableInterface) {
            throw new \InvalidArgumentException(sprintf('Form element "%s" is not clickable.', $name));
        }

        return $button->isClicked();
    }

    protected function getEntityManager(string $class): EntityManagerInterface
    {
        $manager = $this->getDoctrine()->getManagerForClass($class);

        if (!$manager instanceof EntityManagerInterface) {
            throw new \InvalidArgumentException(sprintf('%s is not an entity.', $class));
        }

        return $manager;
    }

    protected function redirectToList(string $name): RedirectResponse
    {
        return $this->redirectToRoute('ruwork_admin_list', [
            'ruwork_admin_entity' => $name,
        ]);
    }

    protected function redirectToCreate(string $name): RedirectResponse
    {
        return $this->redirectToRoute('ruwork_admin_create', [
            'ruwork_admin_entity' => $name,
        ]);
    }

    protected function redirectToEdit(string $name, $entity): RedirectResponse
    {
        $class = \get_class($entity);

        $id = $this->getEntityManager($class)
            ->getClassMetadata($class)
            ->getIdentifierValues($entity);

        return $this->redirectToRoute('ruwork_admin_edit', [
            'ruwork_admin_entity' => $name,
            'id' => reset($id),
        ]);
    }

    protected function getIdField($class)
    {
        if (\is_object($class)) {
            $class = \get_class($class);
        }

        return $this->getEntityManager($class)
            ->getClassMetadata($class)
            ->getSingleIdentifierFieldName();
    }

    protected function createCustomFormBuilder(string $type, $entity, array $options = []): FormBuilderInterface
    {
        return $this->get('form.factory')->createBuilder($type, $entity, $options);
    }

    protected function createEntityFormBuilder(array $fields, $entity, array $options = []): FormBuilderInterface
    {
        return $this->createFormBuilder($entity, $options)
            ->addEventSubscriber(new AddFieldsListener($this->get('security.authorization_checker'), $fields));
    }
}
