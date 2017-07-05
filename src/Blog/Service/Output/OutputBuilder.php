<?php

namespace Blog\Service\Output;

use Symfony\Component\HttpFoundation\JsonResponse;

class OutputBuilder
{
    protected $content;

    protected $statusCode;

    protected $headers;

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    public function getResponse()
    {
        return new JsonResponse($this->content, $this->statusCode);
    }
}
