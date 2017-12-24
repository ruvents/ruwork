<?php

declare(strict_types=1);

namespace Ruwork\DoctrineFilterBundle\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Ruwork\DoctrineFilterBundle\FilterManager;
use Ruwork\DoctrineFilterBundle\Tests\Fixtures\FilterTypes\FilterTypeTest;
use Ruwork\DoctrineFilterBundle\Tests\Fixtures\PsrContainer;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormRegistry;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class FilterManagerTest extends TestCase
{
    /** @var PsrContainer */
    private $container;

    /** @var FormFactory */
    private $formFactory;

    /** @var RequestStack */
    private $requestStack;

    /** @var QueryBuilder */
    private $queryBuilder;

    protected function setUp(): void
    {
        $this->container = new PsrContainer();
        $this->formFactory = (new FormFactory(
            new FormRegistry([new HttpFoundationExtension()], new ResolvedFormTypeFactory())
        ));
        $this->requestStack = new RequestStack();
        $this->queryBuilder = new QueryBuilder($this->createMock(EntityManagerInterface::class));
    }

    public function testLogicException(): void
    {
        $this->expectException(\LogicException::class);

        $serviceId = FilterTypeTest::class;
        $this->container->set($serviceId, new FilterTypeTest());

        (new FilterManager($this->container, $this->formFactory, $this->requestStack))
            ->apply(FilterTypeTest::class, $this->queryBuilder);
    }

    public function testInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->requestStack->push(new Request());

        (new FilterManager($this->container, $this->formFactory, $this->requestStack))
            ->apply(FilterTypeTest::class, $this->queryBuilder);
    }

    public function testApply(): void
    {
        $serviceId = FilterTypeTest::class;
        $this->container->set($serviceId, new FilterTypeTest());

        $this->requestStack->push(new Request());

        $filterManager = new FilterManager($this->container, $this->formFactory, $this->requestStack);

        $filterResult = $filterManager->apply(FilterTypeTest::class, $this->queryBuilder, $options = []);

        $actualValue = $filterResult->getQueryBuilder()->getParameters()[0]->getValue();
        $this->assertEquals(FilterTypeTest::TEST_VALUE, $actualValue);
    }
}
