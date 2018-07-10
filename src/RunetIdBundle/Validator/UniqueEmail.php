<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
final class UniqueEmail extends Constraint
{
    public const NOT_UNIQUE_ERROR = 'b008bd4f-d733-4cbb-9fb9-180ef0c4eb74';

    /**
     * @var string
     */
    public $client = 'default';

    /**
     * @var null|int|string
     */
    public $eventId;

    /**
     * @var null|bool
     */
    public $visible;

    /**
     * @var string
     */
    public $message = 'runet_id.user_with_email_is_already_registered';

    protected static $errorNames = [
        self::NOT_UNIQUE_ERROR => 'NOT_UNIQUE_ERROR',
    ];
}
