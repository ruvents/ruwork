<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\Controller;

use Ruwork\TemplateI18nBundle\Helper\CurrentLocaleTemplateHelper;
use Symfony\Component\HttpFoundation\Response;

final class TemplateI18nController
{
    private $helper;

    public function __construct(CurrentLocaleTemplateHelper $helper)
    {
        $this->helper = $helper;
    }

    public function __invoke(
        string $template,
        int $maxAge = null,
        int $sharedAge = null,
        bool $private = null
    ): Response {
        $response = new Response($this->helper->load($template)->render());

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
