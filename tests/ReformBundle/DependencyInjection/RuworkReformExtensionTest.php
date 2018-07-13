<?php

declare(strict_types=1);

namespace Ruwork\ReformBundle\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

/**
 * @internal
 */
class RuworkReformExtensionTest extends AbstractExtensionTestCase
{
    public function testDefault()
    {
        $this->load([]);

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'ruwork_reform.extension.form_novalidate',
            'form.type_extension',
            [
                'extended_type' => FormType::class,
                'priority' => 512,
            ]
        );

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'ruwork_reform.extension.date_time_default_dti',
            'form.type_extension',
            [
                'extended_type' => DateTimeType::class,
                'priority' => 512,
            ]
        );

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'ruwork_reform.extension.date_default_dti',
            'form.type_extension',
            [
                'extended_type' => DateType::class,
                'priority' => 512,
            ]
        );

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'ruwork_reform.extension.time_default_dti',
            'form.type_extension',
            [
                'extended_type' => TimeType::class,
                'priority' => 512,
            ]
        );
    }

    public function testNovalidateServiceGetsRemoved()
    {
        $this->load([
            'extensions' => [
                'novalidate' => false,
            ],
        ]);

        $this->assertContainerBuilderNotHasService('ruwork_reform.extension.form_novalidate');
    }

    public function testDefaultDTIServicesGetRemoved()
    {
        $this->load([
            'extensions' => [
                'default_datetime_immutable' => false,
            ],
        ]);

        $this->assertContainerBuilderNotHasService('ruwork_reform.extension.date_time_default_dti');
        $this->assertContainerBuilderNotHasService('ruwork_reform.extension.date_default_dti');
        $this->assertContainerBuilderNotHasService('ruwork_reform.extension.time_default_dti');
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return [
            new RuworkReformExtension(),
        ];
    }
}
