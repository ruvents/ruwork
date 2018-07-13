<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\Resolver;

use PHPUnit\Framework\TestCase;
use Ruwork\TemplateI18nBundle\NamingStrategy\NamingStrategyInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
        $expectedTemplate->expects($this->once())
            ->method('getTemplateName')
            ->willReturn('index.en.html.twig');

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

        $requestStack = new RequestStack();
        $request = new Request();
        $request->setLocale('en');
        $requestStack->push($request);

        $resolver = new LocalizedTemplateResolver($strategy, $twig, $requestStack, 'fr');

        $actualTemplate = $resolver->resolve('index.html.twig');

        $this->assertSame('index.en.html.twig', $actualTemplate);
    }
}
