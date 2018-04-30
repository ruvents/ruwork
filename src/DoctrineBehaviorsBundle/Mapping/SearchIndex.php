<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Mapping;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class SearchIndex implements MappingInterface
{
    /**
     * @Required
     *
     * @var <string>
     */
    public $paths = [];

    /**
     * {@inheritdoc}
     */
    public static function getName(): string
    {
        return 'search_index';
    }
}
