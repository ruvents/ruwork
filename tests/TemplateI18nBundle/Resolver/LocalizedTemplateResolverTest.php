<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\Resolver;

use PHPUnit\Framework\TestCase;
use Ruwork\TemplateI18nBundle\NamingStrategy\NamingStrategyInterface;
use Twig\Environment;
use Twig\Template;

/**
 * @internal
 */
class LocalizedTemplateResolverTest extends TestCase
{
    public function testResolve(): void
    {
        $expectedTemplate = $this->createMock(Template::class);
        $expectedTemplate
            ->method('getTemplateName')
            ->willReturn('index.en.html.twig');

        $twig = $this->createMock(Environment::class);
        $twig
            ->method('resolveTemplate')
            ->withConsecutive(
                ['index.en.html.twig'],
                ['index.fr.html.twig'],
                ['index.html.twig']
            )
            ->willReturn($expectedTemplate);

        $strategy = $this->createMock(NamingStrategyInterface::class);
        $strategy
            ->method('getLocalizedName')
            ->willReturnOnConsecutiveCalls(
                'index.en.html.twig',
                'index.fr.html.twig'
            );

        $resolver = new LocalizedTemplateResolver($twig, $strategy, 'fr');

        $actualTemplate = $resolver->resolve('index.html.twig');

        $this->assertSame('index.en.html.twig', $actualTemplate);
    }
}
