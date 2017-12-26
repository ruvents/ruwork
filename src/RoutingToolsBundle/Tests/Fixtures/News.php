<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\Tests\Fixtures;

class News
{
    public $id;
    private $category;

    public function __construct(int $id, string $category)
    {
        $this->id = $id;
        $this->category = $category;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}
