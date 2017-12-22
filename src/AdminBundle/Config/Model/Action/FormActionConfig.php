<?php
declare(strict_types=1);

namespace Ruwork\AdminBundle\Config\Model\Action;

use Ruwork\AdminBundle\Config\Model\AbstractConfig;
use Ruwork\AdminBundle\Config\Model\Field\FormFieldConfig;

/**
 * @property bool              $enabled
 * @property string[]          $requiresGranted
 * @property string            $title
 * @property string            $type
 * @property array             $options
 * @property string            $theme
 * @property FormFieldConfig[] $fields
 */
class FormActionConfig extends AbstractConfig
{
}
