<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\Resolver;

use PHPUnit\Framework\TestCase;
use Ruwork\TemplateI18nBundle\NamingStrategy\NamingStrategyInterface;
use Twig\Environment;
use Twig\Template;

class LocalizedTemplateResolverTest extends TestCase
{
    public function testResolve(): void
    {
        $expectedTemplate = $this->createMock(Template::class);

        $twig = $this->createMock(Environment::class);
        $twig->expects($this->once())
            ->method('resolveTemplate')
            ->with([
                'index.en.html.twig',
                'index.fr.html.twig',
                'index.html.twig',
            ])
            ->willReturn($expectedTemplate);

        $strategy = $this->createMock(NamingStrategyInterface::class);
        $strategy
            ->method('getLocalizedName')
            ->willReturnOnConsecutiveCalls(
                'index.en.html.twig',
                'index.fr.html.twig'
            );

        $resolver = new LocalizedTemplateResolver($strategy, $twig);

        $actualTemplate = $resolver->resolve('index.html.twig', ['en', 'fr']);

        $this->assertSame($expectedTemplate, $actualTemplate);
    }
}
