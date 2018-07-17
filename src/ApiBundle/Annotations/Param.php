<?php

declare(strict_types=1);

namespace Ruwork\ApiBundle\Annotations;

/**
 * @Annotation()
 * @Target({"ANNOTATION"})
 */
class Param
{
    /**
     * @Required()
     *
     * @var string
     */
    public $name;

    /**
     * @Required()
     *
     * @var string
     */
    public $format;

    /**
     * @var string
     */
    public $description;

    /**
     * @var bool
     */
    public $required = false;

    /**
     * @var mixed
     */
    public $data;

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function setValue(string $value)
    {
        $this->name = $value;

        return $this;
    }
}
