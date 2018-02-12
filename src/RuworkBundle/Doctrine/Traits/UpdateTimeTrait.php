<?php

namespace Ruvents\RuworkBundle\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;
use Ruwork\DoctrineBehaviorsBundle\Mapping\PersistTimestamp;
use Ruwork\DoctrineBehaviorsBundle\Mapping\UpdateTimestamp;

trait UpdateTimeTrait
{
    /**
     * @ORM\Column(type="datetimetz_immutable")
     *
     * @PersistTimestamp()
     * @UpdateTimestamp()
     *
     * @var \DateTimeImmutable
     */
    protected $updateTime;

    public function getUpdateTime(): \DateTimeImmutable
    {
        return $this->updateTime;
    }
}
