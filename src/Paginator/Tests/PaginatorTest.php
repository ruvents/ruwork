<?php

declare(strict_types=1);

namespace Ruwork\Paginator\Test;

use PHPUnit\Framework\TestCase;
use Ruwork\Paginator\Provider\ProviderInterface;

class PaginatorTest extends TestCase
{
    /**
     * @dataProvider getBuiltSectionsCounts
     */
    public function testBuildSectionsNumber(int $totalItems, int $perPage, int $proximity, int $current, array $expectedPages): void
    {
        $provider = $this->createMock(ProviderInterface::class);

        $provider->expects($this->once())
            ->method('getTotal')
            ->willReturn($totalItems);

        $provider->expects($this->once())
            ->method('getItems')
            ->willReturn($expectedItems = range(1, random_int(2, 100)));

        /** @var ProviderInterface $provider */
        $paginator = PaginatorBuilder::create()
            ->setProvider($provider)
            ->setProximity($proximity)
            ->setCurrent($current)
            ->setPerPage($perPage)
            ->getPaginator();

        $this->assertSame($totalItems, $paginator->getTotalItems());
        $this->assertSame($expectedItems, $paginator->getItems());

        $lastExpectedSection = end($expectedPages);
        $expectedTotal = end($lastExpectedSection);

        $this->assertSameSize($expectedPages, $paginator);
        $this->assertSame($expectedTotal, $paginator->getTotal());

        foreach ($paginator as $sectionI => $section) {
            $expectedSection = $expectedPages[$sectionI];
            $this->assertSameSize($expectedSection, $section);

            $this->assertSame(count($expectedPages) === $sectionI + 1, $section->isLast());

            foreach ($section as $pageI => $page) {
                $expectedPage = $expectedSection[$pageI];

                $this->assertSame($expectedPage, $page->getNumber());
                $this->assertSame(1 === $expectedPage, $page->isFirst());
                $this->assertSame($current === $expectedPage, $page->isCurrent());

                if ($current === $expectedPage) {
                    $expectedCurrentPage = $page;
                }

                if ($current - 1 === $expectedPage) {
                    $expectedPreviousPage = $page;
                }

                if ($current + 1 === $expectedPage) {
                    $expectedNextPage = $page;
                }
            }
        }

        $this->assertEquals($expectedCurrentPage ?? null, $paginator->getCurrent());
        $this->assertEquals($expectedPreviousPage ?? null, $paginator->getPrevious());
        $this->assertEquals($expectedNextPage ?? null, $paginator->getNext());
    }

    public function getBuiltSectionsCounts()
    {
        return [
            // $totalItems, $perPage, $proximity, $current, $expectedPages
            [0, 10, 10, 1, [[1]]],
            [1, 1, 1, 1, [[1]]],
            [2, 1, 1, 1, [[1, 2]]],
            [10, 1, 1, 1, [[1, 2], [10]]],
            [10, 1, 1, 5, [[1], [4, 5, 6], [10]]],
            [10, 1, 2, 5, [[1], [3, 4, 5, 6, 7], [10]]],
            [10, 1, 3, 5, [[1, 2, 3, 4, 5, 6, 7, 8], [10]]],
            [10, 1, 3, 10, [[1], [7, 8, 9, 10]]],
            [10, 1, 4, 5, [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10]]],
        ];
    }
}
