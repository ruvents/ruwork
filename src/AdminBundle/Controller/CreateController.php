<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\Controller;

use Ruwork\AdminBundle\Config\Model\EntityConfig;
use Ruwork\AdminBundle\Form\Type\ButtonGroupType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateController extends AbstractController
{
    /**
     * @param EntityConfig $entityConfig
     * @param Request      $request
     *
     * @return Response
     */
    public function __invoke(EntityConfig $entityConfig, Request $request): Response
    {
        $createConfig = $entityConfig->create;
        $class = $entityConfig->class;
        $manager = $this->getEntityManager($class);

        if ($attributes = $createConfig->requiresGranted) {
            $this->denyAccessUnlessGranted($attributes, $class);
        }

        $entity = new $class();

        $builder = null === $createConfig->type
            ? $this->createEntityFormBuilder($createConfig->fields, $entity, $createConfig->options)
            : $this->createCustomFormBuilder($createConfig->type, $entity, $createConfig->options);

        $builder
            ->add('__buttons', ButtonGroupType::class, [
                'translation_domain' => 'ruwork_admin',
            ]);

        $builder->get('__buttons')
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

        $form = $builder
            ->getForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($entity);
            $manager->flush();

            if ($this->isClicked($form->get('__buttons'), 'submit_and_list')) {
                return $this->redirectToList($entityConfig->name);
            }

            if ($this->isClicked($form->get('__buttons'), 'submit_and_create')) {
                return $this->redirectToCreate($entityConfig->name);
            }

            return $this->redirectToEdit($entityConfig->name, $entity);
        }

        return $this->render('@RuworkAdmin/create.html.twig', [
            'entity_config' => $entityConfig,
            'form' => $form->createView(),
        ]);
    }
}
