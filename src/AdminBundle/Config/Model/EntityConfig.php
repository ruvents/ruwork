<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\Config\Model;

use Ruwork\AdminBundle\Config\Model\Action\DeleteActionConfig;
use Ruwork\AdminBundle\Config\Model\Action\FormActionConfig;
use Ruwork\AdminBundle\Config\Model\Action\ListActionConfig;

/**
 * @property string             $name
 * @property string             $class
 * @property string[]           $requiresGranted
 * @property ListActionConfig   $list
 * @property FormActionConfig   $create
 * @property FormActionConfig   $edit
 * @property DeleteActionConfig $delete
 */
class EntityConfig extends AbstractConfig
{
}
