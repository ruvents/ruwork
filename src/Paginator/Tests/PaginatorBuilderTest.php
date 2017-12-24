<?php

declare(strict_types=1);

namespace Ruwork\Paginator\Test;

use PHPUnit\Framework\TestCase;
use Ruwork\Paginator\PaginatorBuilder;
use Ruwork\Paginator\Provider\ProviderInterface;

class PaginatorBuilderTest extends TestCase
{
    public function testNoProvider(): void
    {
        $this->expectException(\LogicException::class);

        PaginatorBuilder::create()->getPaginator();
    }

    /**
     * @dataProvider getNonPositiveIntegers
     */
    public function testInvalidPerPageException(int $value): void
    {
        $this->expectException(\InvalidArgumentException::class);

        PaginatorBuilder::create()->setPerPage($value);
    }

    /**
     * @dataProvider getNonPositiveIntegers
     */
    public function testInvalidProximityException(int $value): void
    {
        $this->expectException(\InvalidArgumentException::class);

        PaginatorBuilder::create()->setProximity($value);
    }

    public function testUnexpectedProviderTotalException(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        $provider = $this->createMock(ProviderInterface::class);

        $provider->expects($this->once())
            ->method('getTotal')
            ->willReturn(-1);

        /* @var ProviderInterface $provider */

        PaginatorBuilder::create()
            ->setProvider($provider)
            ->getPaginator();
    }

    public function testCurrentPageOutOfRangeException(): void
    {
        $this->expectException(\Ruwork\Paginator\Exception\PageOutOfRangeException::class);
        $this->expectExceptionMessage('Page 2 is out of range [1, 1].');

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
