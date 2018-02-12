<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Alias extends Constraint
{
    const MATCH_PATTERN = '#^[a-z0-9-]+$#';
    const HTML_MATCH_PATTERN = '^[a-z0-9-]+$';
    const ROUTE_REQUIREMENT = '[a-z0-9-]+';
    const CLEAN_PATTERN = '#[^a-z0-9-]+#';

    /**
     * @var string
     */
    public $pattern = self::MATCH_PATTERN;

    /**
     * @var string
     */
    public $htmlPattern = self::HTML_MATCH_PATTERN;

    /**
     * @var int
     */
    public $maxLength = 120;

    /**
     * @var null|string
     */
    public $entityClass;

    /**
     * @var string
     */
    public $repositoryMethod = 'findBy';

    /**
     * @var string
     */
    public $regexMessage = 'This value is not valid.';

    /**
     * @var string
     */
    public $maxLengthMessage = 'This value is too long. It should have {{ limit }} character or less.|This value is too long. It should have {{ limit }} characters or less.';

    /**
     * @var string
     */
    public $notUniqueMessage = 'This value is already used.';
}
