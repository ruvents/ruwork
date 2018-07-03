<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\HttpFoundation;

final class FrujaxHeaders
{
    public const FRUJAX = 'Frujax';
    public const FRUJAX_HIDE_FORM_ERRORS = 'Frujax-Hide-Form-Errors';
    public const FRUJAX_INTERCEPT_REDIRECT = 'Frujax-Intercept-Redirect';
    public const FRUJAX_NAME = 'Frujax-Name';
    public const FRUJAX_REDIRECT_LOCATION = 'Frujax-Redirect-Location';
    public const FRUJAX_REDIRECT_STATUS_CODE = 'Frujax-Redirect-Status-Code';
    public const FRUJAX_TITLE = 'Frujax-Title';
    public const FRUJAX_URL = 'Frujax-Url';

    private function __construct()
    {
    }
}
