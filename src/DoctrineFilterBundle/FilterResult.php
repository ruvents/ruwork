<?php

namespace Ruwork\DoctrineFilterBundle;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

final class FilterResult
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var array
     */
    private $options;

    public function __construct(FormInterface $form, QueryBuilder $queryBuilder, array $options)
    {
        $this->form = $form;
        $this->queryBuilder = $queryBuilder;
        $this->options = $options;
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function createView(): FormView
    {
        return $this->getForm()->createView();
    }
}
