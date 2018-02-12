<?php

namespace Ruvents\RuworkBundle\ControllerExtra\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation()
 */
class Redirect extends ConfigurationAnnotation
{
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
     * @var bool
     */
    private $permanent = false;

    public function getCondition(): string
    {
        return $this->condition;
    }

    public function setCondition(string $condition)
    {
        $this->condition = $condition;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    public function getPermanent(): bool
    {
        return $this->permanent;
    }

    public function setPermanent(bool $permanent)
    {
        $this->permanent = $permanent;
    }

    public function setValue(string $value)
    {
        $this->setUrl($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getAliasName()
    {
        return 'ruvents_ruwork.redirect';
    }

    /**
     * {@inheritdoc}
     */
    public function allowArray()
    {
        return true;
    }
}
