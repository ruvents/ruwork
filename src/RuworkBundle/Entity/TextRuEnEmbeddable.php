<?php

namespace Ruvents\RuworkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ruwork\DoctrineBehaviorsBundle\Multilingual\AbstractMultilingual;

/**
 * @ORM\Embeddable()
 */
class TextRuEnEmbeddable extends AbstractMultilingual
{
    use TextRuEnTrait;
}
