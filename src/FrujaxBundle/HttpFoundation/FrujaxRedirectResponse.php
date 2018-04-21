<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class FrujaxRedirectResponse extends Response
{
    private $targetUrl;

    public function __construct(string $url, array $headers = [])
    {
        parent::__construct('', self::HTTP_OK, $headers);
        $this->setTargetUrl($url);
    }

    public static function createFromRedirectResponse(RedirectResponse $redirect): self
    {
        $frujax = new self($redirect->getTargetUrl());

        $frujax->version = $redirect->version;
        $frujax->charset = $redirect->charset;
        $frujax->headers = clone $redirect->headers;
        $frujax->headers->remove('Location');

        $frujax->setTargetUrl($redirect->getTargetUrl());

        return $frujax;
    }

    public function getTargetUrl(): string
    {
        return $this->targetUrl;
    }

    public function setTargetUrl(string $targetUrl)
    {
        $targetUrl = \trim($targetUrl);

        if ('' === $targetUrl) {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
        }

        $this->targetUrl = $targetUrl;
        $this->headers->set(FrujaxHeaders::FRUJAX_REDIRECT_URL, $targetUrl);

        return $this;
    }
}
