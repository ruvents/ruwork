<?php

declare(strict_types=1);

namespace Ruwork\ReformBundle;

use PHPUnit\Framework\TestCase;
use Ruwork\ReformBundle\DependencyInjection\RuworkReformExtension;

class RuworkReformBundleTest extends TestCase
{
    public function testExtensionClass()
    {
        $bundle = new RuworkReformBundle();

        $this->assertInstanceOf(RuworkReformExtension::class, $bundle->getContainerExtension());
    }
}
