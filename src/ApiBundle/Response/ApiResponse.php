<?php

namespace Ruwork\ApiBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    /**
     * @param mixed $data
     * @param int   $statusCode
     * @param array $headers
     */
    public function __construct($data, int $statusCode = 200, array $headers = [])
    {
        $this->encodingOptions = JSON_UNESCAPED_UNICODE;

        parent::__construct($data, $statusCode, array_merge([
            'Content-Type' => 'application/json; charset=utf-8',
        ], $headers));
    }
}
