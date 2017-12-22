<?php

declare(strict_types=1);

namespace Ruwork\Paginator\Test\Provider;

use PHPUnit\Framework\TestCase;

class IterableProviderTest extends TestCase
{
    /**
     * @dataProvider getData
     */
    public function testProvider(iterable $data, int $offset, int $limit, array $expectedItems): void
    {
        $provider = new IterableProvider($data);

        if ($data instanceof \Traversable) {
            $data = iterator_to_array($data);
        }

        $this->assertSame(count($data), $provider->getTotal());
        $this->assertSame($expectedItems, $provider->getItems($offset, $limit));
    }

    public function getData()
    {
        return [
            [range(1, 100), 2, 3, [3, 4, 5]],
            [new \ArrayIterator(range(1, 100)), 0, 100, range(1, 100)],
            [new \ArrayIterator(range(1, 100)), 0, 1000, range(1, 100)],
        ];
    }
}
