<?php

namespace Ruvents\RuworkBundle\Monolog\Processor;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestProcessor
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function processRecord(array $record): array
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request) {
            $record['extra']['query'] = $request->query->all();
            $record['extra']['request'] = $request->request->all();
            $record['extra']['xml_http_request'] = $request->isXmlHttpRequest() ? 'true' : 'false';
        }

        return $record;
    }
}
