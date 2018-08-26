<?php

declare(strict_types=1);

namespace Ruwork\Reform\Extension;

use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeImmutableToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Test\FormIntegrationTestCase;

/**
 * @internal
 */
class DateTimeTypeDefaultDTIExtensionTest extends FormIntegrationTestCase
{
    protected function setUp()
    {
        if (!\class_exists(DateTimeImmutableToDateTimeTransformer::class)) {
            $this->markTestSkipped();
        }

        parent::setUp();
    }

    public function testInputOptionDefaultsToDTI()
    {
        $form = $this->factory->create(DateTimeType::class);

        $this->assertSame('datetime_immutable', $form->getConfig()->getOption('input'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getTypeExtensions()
    {
        return [
            new DateTimeTypeDefaultDTIExtension(),
        ];
    }
}
