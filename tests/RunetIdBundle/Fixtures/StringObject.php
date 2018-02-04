<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Fixtures;

class StringObject
{
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
