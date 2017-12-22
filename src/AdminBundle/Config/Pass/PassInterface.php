<?php
declare(strict_types=1);

namespace Ruwork\AdminBundle\Config\Pass;

use Ruwork\AdminBundle\Config\Model\Config;

interface PassInterface
{
    public function process(Config $config, array $data);
}
