<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Asset\VersionStrategy;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class FilemtimeStrategyTest extends TestCase
{
    public function testGetVersionOfExistingFile(): void
    {
        $strategy = new FilemtimeStrategy();

        $this->assertSame((string) \filemtime(__FILE__), $strategy->getVersion(__FILE__));
    }

    public function testGetVersionOfNonExistingFile(): void
    {
        $strategy = new FilemtimeStrategy();

        $this->assertSame('', $strategy->getVersion('noop'));
    }

    public function testApplyVersionToExistingFile(): void
    {
        $strategy = new FilemtimeStrategy();
        $path = __FILE__;
        $expected = \sprintf('%s?t=%d', $path, \filemtime($path));

        $this->assertSame($expected, $strategy->applyVersion($path));
    }

    public function testApplyVersionToNonExistingFile(): void
    {
        $strategy = new FilemtimeStrategy();
        $path = __FILE__.'_';

        $this->assertSame($path, $strategy->applyVersion($path));
    }
}
