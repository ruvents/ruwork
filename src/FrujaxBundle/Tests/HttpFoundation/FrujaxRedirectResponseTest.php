<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\Tests\HttpFoundation;

use PHPUnit\Framework\TestCase;
use Ruwork\FrujaxBundle\HttpFoundation\FrujaxRedirectResponse;

class FrujaxRedirectResponseTest extends TestCase
{
    public function testEmptyTargetException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot redirect to an empty URL.');

        new FrujaxRedirectResponse('');
    }
}
