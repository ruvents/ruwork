<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\ControllerAnnotations;

use Doctrine\Common\Annotations\Annotation\Required;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation()
 */
final class Redirect extends ConfigurationAnnotation
{
    public const NAME = 'redirects';

    /**
     * @Required()
     *
     * @var string
     */
    private $condition;

    /**
     * @var null|array [route, [name => value]]
     */
    private $target;

    /**
     * @var null|string
     */
    private $targetExpression;

    /**
     * @var int
     */
    private $status = 302;

    public function getCondition(): string
    {
        return $this->condition;
    }

    public function setCondition(string $condition): void
    {
        $this->condition = $condition;
    }

    public function setValue(string $value): void
    {
        $this->setCondition($value);
    }

    public function getTarget(): ?array
    {
        return $this->target;
    }

    /**
     * @param null|array|string $target
     */
    public function setTarget($target): void
    {
        if (null !== $target) {
            $target = (array) $target;

            if (empty($target[0])) {
                throw new \InvalidArgumentException('Target route must not be empty.');
            }
        }

        $this->target = $target;
    }

    public function getTargetExpression(): ?string
    {
        return $this->targetExpression;
    }

    public function setTargetExpression(?string $targetExpression): void
    {
        $this->targetExpression = $targetExpression;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * {@inheritdoc}
     */
    public function getAliasName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function allowArray()
    {
        return true;
    }
}
