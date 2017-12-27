<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\HttpFoundation;

use PHPUnit\Framework\TestCase;

class FrujaxRedirectResponseTest extends TestCase
{
    public function testEmptyTargetException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot redirect to an empty URL.');

        new FrujaxRedirectResponse('');
    }
}
