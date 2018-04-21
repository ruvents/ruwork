<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\HttpFoundation;

final class FrujaxHeaders
{
    public const FRUJAX = 'Frujax';
    public const FRUJAX_BLOCK = 'Frujax-Block';
    public const FRUJAX_INTERCEPT_REDIRECT = 'Frujax-Intercept-Redirect';
    public const FRUJAX_REDIRECT_URL = 'Frujax-Redirect-Url';
    public const FRUJAX_TITLE = 'Frujax-Title';
    public const FRUJAX_URL = 'Frujax-Url';

    private function __construct()
    {
    }
}
