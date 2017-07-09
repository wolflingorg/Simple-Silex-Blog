<?php

namespace Blog\Service\Output;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OutputBuilder
{
    protected $responseCode = null;

    protected $headers = [];

    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    public function getResponse(Request $request, $content = '')
    {
        $code = $this->getProperResponseCode($request);

        return new JsonResponse($content, $code, $this->headers);
    }

    protected function getProperResponseCode(Request $request)
    {
        if (!is_null($this->responseCode)) {
            return $this->responseCode;
        }

        switch ($request->getMethod()) {
            case Request::METHOD_GET;
            case Request::METHOD_PUT;
            case Request::METHOD_DELETE:
                $code = Response::HTTP_OK;
                break;
            case Request::METHOD_POST:
                $code = Response::HTTP_CREATED;
                break;
            default:
                $code = 406;
                break;
        }

        return $code;
    }
}
