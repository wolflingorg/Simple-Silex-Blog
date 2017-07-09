<?php

namespace Blog\Service\Output;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ResponseListener implements EventSubscriberInterface
{
    private $builder;

    public function __construct(OutputBuilder $builder)
    {
        $this->builder = $builder;
    }

    public static function getSubscribedEvents()
    {
        // set priority to 10 in order to prevent "string to response" auto convert
        return [
            KernelEvents::VIEW => ['onKernelView', 10],
        ];
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $result = $event->getControllerResult();

        if (!$result instanceof Response) {
            $event->setResponse($this->builder->getResponse($request, $result));
        }
    }
}
