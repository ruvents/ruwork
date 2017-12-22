<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Validator;

use Doctrine\Common\Annotations\Annotation\Target;
use Symfony\Component\Validator\Constraints\Composite as AbstractComposite;

/**
 * @Annotation()
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class ValidMultilingual extends AbstractComposite
{
    /**
     * @var array
     */
    public $locales = [];

    public function __construct($options = null)
    {
        $locales = &$options['locales'];

        if (isset($options['value'])) {
            $locales = &$options['value'];
        }

        foreach ($locales as &$localeConstraints) {
            if (is_array($localeConstraints)) {
                $localeConstraints = new Composite(['constraints' => $localeConstraints]);
            }
        }

        unset($localeConstraints, $locales);

        parent::__construct($options);
    }

    public function getDefaultOption(): string
    {
        return 'locales';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions(): array
    {
        return ['locales'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getCompositeOption(): string
    {
        return 'locales';
    }
}
