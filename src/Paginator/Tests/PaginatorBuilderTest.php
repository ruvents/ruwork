<?php

declare(strict_types=1);

namespace Ruwork\Paginator\Test;

use PHPUnit\Framework\TestCase;
use Ruwork\Paginator\Provider\ProviderInterface;

class PaginatorBuilderTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testNoProvider(): void
    {
        PaginatorBuilder::create()->getPaginator();
    }

    /**
     * @dataProvider getNonPositiveIntegers
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidPerPageException(int $value): void
    {
        PaginatorBuilder::create()->setPerPage($value);
    }

    /**
     * @dataProvider getNonPositiveIntegers
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidProximityException(int $value): void
    {
        PaginatorBuilder::create()->setProximity($value);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testUnexpectedProviderTotalException(): void
    {
        $provider = $this->createMock(ProviderInterface::class);

        $provider->expects($this->once())
            ->method('getTotal')
            ->willReturn(-1);

        /* @var ProviderInterface $provider */

        PaginatorBuilder::create()
            ->setProvider($provider)
            ->getPaginator();
    }

    /**
     * @expectedException \Ruwork\Paginator\Exception\PageOutOfRangeException
     * @expectedExceptionMessage Page 2 is out of range [1, 1].
     */
    public function testCurrentPageOutOfRangeException(): void
    {
        $provider = $this->createMock(ProviderInterface::class);

        $provider->expects($this->once())
            ->method('getTotal')
            ->willReturn(1);

        /* @var ProviderInterface $provider */

        PaginatorBuilder::create()
            ->setProvider($provider)
            ->setCurrent(2)
            ->getPaginator();
    }

    public function getNonPositiveIntegers()
    {
        return [
            [-100],
            [-2],
            [0],
        ];
    }
}
