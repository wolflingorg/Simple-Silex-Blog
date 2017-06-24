<?php
namespace Blog\Service\CommandBus;

use SimpleBus\Message\CallableResolver\CallableResolver;
use SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable;

class ServiceLocatorAwareCallableResolver implements CallableResolver
{
    public function resolve($maybeCallable)
    {
        if (is_string($maybeCallable)) {
            return $this->resolve([$maybeCallable, []]);
        }

        if (is_array($maybeCallable) && count($maybeCallable) === 2) {
            list($class, $args) = $maybeCallable;
            if (class_exists($class) && is_array($args)) {
                return $this->resolve(new $class(...$args));
            }
        }

        // to make the upgrade process easier: auto-select the "handle" method
        if (is_object($maybeCallable) && $maybeCallable instanceof CommandHandler) {
            return [$maybeCallable, 'handle'];
        }

        // to make the upgrade process easier: auto-select the "notify" method
        if (is_object($maybeCallable) && $maybeCallable instanceof EventHandler) {
            return [$maybeCallable, 'notify'];
        }

        throw CouldNotResolveCallable::createFor($maybeCallable);
    }
}