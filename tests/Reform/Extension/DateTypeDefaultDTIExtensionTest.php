<?php

declare(strict_types=1);

namespace Ruwork\Reform\Extension;

use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeImmutableToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Test\FormIntegrationTestCase;

/**
 * @internal
 */
class DateTypeDefaultDTIExtensionTest extends FormIntegrationTestCase
{
    protected function setUp()
    {
        if (!class_exists(DateTimeImmutableToDateTimeTransformer::class)) {
            $this->markTestSkipped();
        }

        parent::setUp();
    }

    public function testInputOptionDefaultsToDTI()
    {
        $form = $this->factory->create(DateType::class);

        $this->assertSame('datetime_immutable', $form->getConfig()->getOption('input'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getTypeExtensions()
    {
        return [
            new DateTypeDefaultDTIExtension(),
        ];
    }
}
