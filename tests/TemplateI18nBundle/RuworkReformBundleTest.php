<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle;

use PHPUnit\Framework\TestCase;
use Ruwork\TemplateI18nBundle\DependencyInjection\RuworkTemplateI18nExtension;

/**
 * @internal
 */
class RuworkReformBundleTest extends TestCase
{
    public function testExtensionClass(): void
    {
        $extension = (new RuworkTemplateI18nBundle())->getContainerExtension();

        $this->assertInstanceOf(RuworkTemplateI18nExtension::class, $extension);
    }
}
