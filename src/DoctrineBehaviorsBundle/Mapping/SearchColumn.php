<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Mapping;

/**
 * @Annotation()
 * @Target("CLASS")
 */
final class SearchColumn
{
    /**
     * @Required()
     *
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @Required()
     *
     * @var <string>
     */
    public $propertyPaths = [];

    /**
     * @var \Doctrine\ORM\Mapping\Index
     */
    public $index;

    public function setValue($value)
    {
        $this->name = $value;

        return $this;
    }
}
