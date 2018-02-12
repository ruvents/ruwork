<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle;

use PHPUnit\Framework\TestCase;
use Ruwork\RuworkBundle\DependencyInjection\RuworkExtension;

class RuworkBundleTest extends TestCase
{
    public function testExtensionClass()
    {
        $extension = (new RuworkBundle())->getContainerExtension();

        $this->assertNotNull($extension);
        $this->assertInstanceOf(RuworkExtension::class, $extension);
    }
}
