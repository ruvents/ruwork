<?php

declare(strict_types=1);

namespace Ruwork\FeatureBundle\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation()
 * @Target({"CLASS", "METHOD"})
 */
final class Feature extends ConfigurationAnnotation
{
    private $name;
    private $message;
    private $statusCode = 404;

    public function __construct(array $values)
    {
        parent::__construct($values);

        if (!$this->name) {
            throw new \InvalidArgumentException('Feature name is required.');
        }
    }

    public function setValue(string $value): void
    {
        $this->setName($value);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getAliasName()
    {
        return 'ruwork_feature';
    }

    /**
     * {@inheritdoc}
     */
    public function allowArray()
    {
        return true;
    }
}
