<?php

namespace Ruvents\RuworkBundle\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;
use Ruwork\DoctrineBehaviorsBundle\Mapping\PersistTimestamp;

trait CreationTimeTrait
{
    /**
     * @ORM\Column(type="datetimetz_immutable")
     *
     * @PersistTimestamp()
     *
     * @var \DateTimeImmutable
     */
    protected $creationTime;

    public function getCreationTime(): \DateTimeImmutable
    {
        return $this->creationTime;
    }
}
