<?php

namespace Ruwork\ApiBundle\Annotations;

/**
 * @Annotation()
 * @Target({"METHOD"})
 */
class Doc
{
    /**
     * @Required()
     *
     * @var string
     */
    public $title;

    /**
     * @var array<string>
     */
    public $methods = [];

    /**
     * @var string
     */
    public $endpoint;

    /**
     * @var array<Ruwork\ApiBundle\Annotations\Param>
     */
    public $params = [];

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $priority = 0;

    /**
     * @var bool
     */
    public $requiresAuth = false;

    /**
     * @var array<string>
     */
    public $displayRoles = [];

    /**
     * @var bool
     */
    public $deprecated = false;

    /**
     * @var string
     */
    public $result;

    /**
     * @var string
     */
    public $block;

    /**
     * @var mixed
     */
    public $data;

    public function setValue(string $value)
    {
        $this->title = $value;

        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->title;
    }
}
