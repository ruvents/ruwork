<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class FrujaxRedirectResponse extends Response
{
    private $targetUrl;

    public function __construct(string $url, int $status = 200, array $headers = [])
    {
        parent::__construct('', $status, $headers);
        $this->setTargetUrl($url);
    }

    public static function createFromRedirectResponse(RedirectResponse $redirect): self
    {
        $frujax = new self($redirect->getTargetUrl());

        $frujax->setProtocolVersion($redirect->getProtocolVersion());
        $frujax->setCharset($redirect->getCharset());
        $frujax->headers = clone $redirect->headers;
        $frujax->headers->remove('Location');

        // we must set url again after replacing the headers
        $frujax->setTargetUrl($redirect->getTargetUrl());

        return $frujax;
    }

    public function getTargetUrl(): string
    {
        return $this->targetUrl;
    }

    public function setTargetUrl(string $targetUrl)
    {
        if (empty($targetUrl)) {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
        }

        $this->targetUrl = $targetUrl;
        $this->headers->set('Frujax-Redirect-Url', $targetUrl);

        return $this;
    }
}
