<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Element;

/**
 * @deprecated since 0.12.5 and will be removed in 0.13. Use ProductElement instead.
 */
abstract class AbstractElement extends ProductElement
{
    abstract public function getRunetId(): int;
}
