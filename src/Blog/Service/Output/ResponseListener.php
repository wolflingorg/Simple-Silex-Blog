<?php

namespace Blog\Service\Output;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Listens Kernel Get Response For Controller Result Event
 * and passes Controllers Response to the correct OutputBuilder
 *
 * @package Blog\Service\Output
 */
class ResponseListener implements EventSubscriberInterface
{
    private $builder;

    /**
     * @param OutputBuilder $builder
     */
    public function __construct(OutputBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // set priority to 10 in order to prevent "string to response" auto convert
        return [
            KernelEvents::VIEW => ['onKernelView', 10],
        ];
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $result = $event->getControllerResult();

        if (!$result instanceof Response) {
            $event->setResponse($this->builder->getResponse($request, $result));
        }
    }
}
