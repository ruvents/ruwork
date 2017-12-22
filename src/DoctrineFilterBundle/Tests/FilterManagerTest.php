<?php

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

    /**
     * @expectedException \LogicException
     */
    public function testLogicException()
    {
        $serviceId = FilterTypeTest::class;
        $this->container->set($serviceId, new FilterTypeTest());

        (new FilterManager($this->container, $this->formFactory, $this->requestStack))
            ->apply(FilterTypeTest::class, $this->queryBuilder);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentException()
    {
        $this->requestStack->push(new Request());

        (new FilterManager($this->container, $this->formFactory, $this->requestStack))
            ->apply(FilterTypeTest::class, $this->queryBuilder);
    }

    public function testApply()
    {
        $serviceId = FilterTypeTest::class;
        $this->container->set($serviceId, new FilterTypeTest());

        $this->requestStack->push(new Request());

        $filterManager = new FilterManager($this->container, $this->formFactory, $this->requestStack);

        $filterResult = $filterManager->apply(FilterTypeTest::class, $this->queryBuilder, $options = []);

        $actualValue = $filterResult->getQueryBuilder()->getParameters()[0]->getValue();
        $this->assertEquals(FilterTypeTest::TEST_VALUE, $actualValue);
    }

    protected function setUp()
    {
        $this->container = new PsrContainer();
        $this->formFactory = (new FormFactory(
            new FormRegistry([new HttpFoundationExtension()], new ResolvedFormTypeFactory())
        ));
        $this->requestStack = new RequestStack();
        $this->queryBuilder = new QueryBuilder($this->createMock(EntityManagerInterface::class));
    }
}
