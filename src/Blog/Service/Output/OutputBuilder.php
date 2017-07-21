<?php

namespace Blog\Service\Output;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Builds the correct REST Response
 *
 * @package Blog\Service\Output
 */
class OutputBuilder
{
    protected $responseCode = null;

    protected $headers = [];

    protected $supported = [
        'application/json',
        'application/xml'
    ];

    private $serializer;

    /**
     * @param SerializerInterface $serializer
     * @param null $supported
     */
    public function __construct(SerializerInterface $serializer, $supported = null)
    {
        $this->serializer = $serializer;

        if (!empty($supported)) {
            $this->supported = $supported;
        }
    }

    /**
     * @param $responseCode
     *
     * @return $this
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    /**
     * @param $headers
     *
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param Request $request
     * @param array $content
     *
     * @return Response
     */
    public function getResponse(Request $request, $content = [])
    {
        try {
            $code = $this->getProperResponseCode($request);
            $content = $this->serializer->serialize($content, $this->getProperResponseFormat($request));
        } catch (BadRequestHttpException $e) {
            $content = $this->serializer->serialize([$e->getMessage()], $request->getFormat($this->supported[0]));
            $code = Response::HTTP_BAD_REQUEST;
        }

        return new Response(
            $content,
            $code,
            $this->headers
        );
    }

    /**
     * Returns the correct response code based on Requests Method Type
     *
     * @param Request $request
     *
     * @return int|null
     */
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
                throw new BadRequestHttpException('Unsupported method');
        }

        return $code;
    }

    /**
     * Returns correct response format based on Requests Content-Type
     *
     * @param Request $request
     *
     * @return string
     */
    protected function getProperResponseFormat(Request $request)
    {
        $accepted = $request->getAcceptableContentTypes();
        array_push($accepted, $request->headers->get('Content-Type'));

        foreach (array_intersect($accepted, $this->supported) as $type) {
            return $request->getFormat($type);
        }

        throw new BadRequestHttpException('Unsupported Content-Type or Accept header');
    }
}
