<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\Controller;

use Ruwork\TemplateI18nBundle\Localizer\TemplateLocalizer;
use Symfony\Component\HttpFoundation\Response;

final class TemplateI18nController
{
    private $localizer;

    public function __construct(TemplateLocalizer $localizer)
    {
        $this->localizer = $localizer;
    }

    public function __invoke(
        string $_locale,
        string $template,
        int $maxAge = null,
        int $sharedAge = null,
        bool $private = null
    ): Response {
        $template = $this->localizer->load($template, [$_locale]);
        $response = new Response($template->render());

        if ($maxAge) {
            $response->setMaxAge($maxAge);
        }

        if ($sharedAge) {
            $response->setSharedMaxAge($sharedAge);
        }

        if ($private) {
            $response->setPrivate();
        } elseif (false === $private || (null === $private && ($maxAge || $sharedAge))) {
            $response->setPublic();
        }

        return $response;
    }
}
