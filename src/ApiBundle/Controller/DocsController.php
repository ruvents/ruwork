<?php

namespace Ruwork\ApiBundle\Controller;

use Ruwork\ApiBundle\DocsExtractor;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class DocsController
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var DocsExtractor
     */
    private $extractor;

    public function __construct(Environment $twig, DocsExtractor $extractor)
    {
        $this->extractor = $extractor;
        $this->twig = $twig;
    }

    public function __invoke(): Response
    {
        return new Response($this->twig->render('@RuworkApi/docs.html.twig', [
            'docs' => $this->extractor->getDocs(),
        ]));
    }
}
