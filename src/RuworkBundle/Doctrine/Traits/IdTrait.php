<?php

namespace Ruvents\RuworkBundle\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;

trait IdTrait
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue("IDENTITY")
     *
     * @var int
     */
    protected $id = 0;

    public function getId(): int
    {
        return $this->id;
    }
}
