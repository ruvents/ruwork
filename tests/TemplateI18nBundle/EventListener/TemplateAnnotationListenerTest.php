<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\EventListener;

use PHPUnit\Framework\TestCase;
use Ruwork\TemplateI18nBundle\Resolver\LocalizedTemplateResolverInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template as TemplateConfig;
use Sensio\Bundle\FrameworkExtraBundle\EventListener\TemplateListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @internal
 */
class TemplateAnnotationListenerTest extends TestCase
{
    public function testEventPriorityHigher(): void
    {
        $sensioEventConfig = TemplateListener::getSubscribedEvents()[KernelEvents::CONTROLLER];
        $sensioPriority = \is_array($sensioEventConfig) ? ($sensioEventConfig[1] ?? 0) : 0;

        $this->assertLessThan(
            $sensioPriority,
            TemplateAnnotationListener::getSubscribedEvents()[KernelEvents::CONTROLLER][1] ?? 0
        );
    }

    public function testChangesTemplate(): void
    {
        $resolver = $this->createMock(LocalizedTemplateResolverInterface::class);
        $resolver->expects($this->once())
            ->method('resolve')
            ->willReturn('new.html.twig');

        $config = new TemplateConfig(['template' => 'old.html.twig']);

        $request = new Request([], [], ['_template' => $config]);

        $event = $this->createMock(FilterControllerEvent::class);
        $event->expects($this->once())
            ->method('getRequest')
            ->willReturn($request);

        (new TemplateAnnotationListener($resolver))->onKernelController($event);

        $this->assertSame('new.html.twig', $config->getTemplate());
    }

    public function testDoesNotThrowWithoutTemplate(): void
    {
        $resolver = $this->createMock(LocalizedTemplateResolverInterface::class);

        $event = $this->createMock(FilterControllerEvent::class);
        $event->expects($this->once())
            ->method('getRequest')
            ->willReturn(new Request());

        (new TemplateAnnotationListener($resolver))->onKernelController($event);

        $this->addToAssertionCount(1);
    }
}
