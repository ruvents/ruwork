<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\ControllerExtra\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation()
 */
final class Redirect extends ConfigurationAnnotation
{
    const NAME = 'redirects';

    /**
     * @Required()
     *
     * @var string
     */
    private $condition;

    /**
     * @Required()
     *
     * @var string
     */
    private $url;

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

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function setValue(string $value): void
    {
        $this->setUrl($value);
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
