<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Ruwork\FrujaxBundle\DependencyInjection\RuworkFrujaxExtension;
use Ruwork\FrujaxBundle\EventListener\FrujaxListener;
use Symfony\Component\DependencyInjection\Alias;

class RuworkFrujaxExtensionTest extends AbstractExtensionTestCase
{
    public function test(): void
    {
        $this->load();
        $this->container->setAlias('test.'.FrujaxListener::class, new Alias(FrujaxListener::class));
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithTag('test.'.FrujaxListener::class, 'kernel.event_subscriber');
    }

    protected function getContainerExtensions(): array
    {
        return [
            new RuworkFrujaxExtension(),
        ];
    }
}
