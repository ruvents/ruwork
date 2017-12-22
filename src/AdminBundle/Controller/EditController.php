<?php
declare(strict_types=1);

namespace Ruwork\AdminBundle\Controller;

use Ruwork\AdminBundle\Config\Model\EntityConfig;
use Ruwork\AdminBundle\Form\Type\ButtonGroupType;
use Ruwork\AdminBundle\Form\Type\DeleteType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EditController extends AbstractController
{
    /**
     * @param string       $id
     * @param EntityConfig $entityConfig
     * @param Request      $request
     *
     * @return Response
     */
    public function __invoke(string $id, EntityConfig $entityConfig, Request $request): Response
    {
        $editConfig = $entityConfig->edit;
        $class = $entityConfig->class;
        $manager = $this->getEntityManager($class);

        if ($attributes = $editConfig->requiresGranted) {
            $this->denyAccessUnlessGranted($attributes, $class);
        }

        $entity = $manager->find($class, $id);

        if (null === $entity) {
            throw $this->createNotFoundException();
        }

        $builder = null === $editConfig->type
            ? $this->createEntityFormBuilder($editConfig->fields, $entity, $editConfig->options)
            : $this->createCustomFormBuilder($editConfig->type, $entity, $editConfig->options);

        $builder->add('__buttons', ButtonGroupType::class, [
            'translation_domain' => 'ruwork_admin',
        ]);

        $buttonsBuilder = $builder->get('__buttons')
            ->add('submit', SubmitType::class, [
                'label' => 'Save and continue editing',
                'attr' => ['class' => 'btn-success'],
            ])
            ->add('submit_and_list', SubmitType::class, [
                'label' => 'Save and go to list',
                'attr' => ['class' => 'btn-primary'],
            ])
            ->add('submit_and_create', SubmitType::class, [
                'label' => 'Save and create new',
                'attr' => ['class' => 'btn-secondary'],
            ]);

        if ($entityConfig->delete->enabled) {
            $buttonsBuilder->add('delete', DeleteType::class, ['attr' => ['class' => 'btn-danger']]);
        }

        $form = $builder
            ->getForm()
            ->handleRequest($request);

        if ($this->isClicked($form->get('__buttons'), 'delete')) {
            $manager->remove($entity);
            $manager->flush();

            return $this->redirectToList($entityConfig->name);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            if ($this->isClicked($form->get('__buttons'), 'submit_and_list')) {
                return $this->redirectToList($entityConfig->name);
            }

            if ($this->isClicked($form->get('__buttons'), 'submit_and_create')) {
                return $this->redirectToCreate($entityConfig->name);
            }

            return $this->redirectToEdit($entityConfig->name, $entity);
        }

        return $this->render('@RuworkAdmin/edit.html.twig', [
            'entity_config' => $entityConfig,
            'form' => $form->createView(),
        ]);
    }
}
