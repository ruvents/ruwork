<?php

declare(strict_types=1);

namespace Ruwork\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractApiController extends AbstractController
{
    use ApiControllerTrait;
}
