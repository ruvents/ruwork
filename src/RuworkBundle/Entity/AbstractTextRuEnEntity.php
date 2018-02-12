<?php

declare(strict_types=1);

namespace Ruvents\RuworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ruvents\RuworkBundle\Doctrine\Traits\IdTrait;
use Ruwork\DoctrineBehaviorsBundle\Multilingual\AbstractMultilingual;

/**
 * @ORM\MappedSuperclass()
 */
abstract class AbstractTextRuEnEntity extends AbstractMultilingual
{
    use IdTrait;
    use TextRuEnTrait;
}
