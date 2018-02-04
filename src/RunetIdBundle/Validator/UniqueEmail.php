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
    const NOT_UNIQUE_ERROR = 'b008bd4f-d733-4cbb-9fb9-180ef0c4eb74';

    /**
     * @var string
     */
    public $client = 'default';

    /**
     * @var string
     */
    public $message = 'runet_id.user_with_email_exists_log_in_with_runet_id';

    protected static $errorNames = [
        self::NOT_UNIQUE_ERROR => 'NOT_UNIQUE_ERROR',
    ];
}
