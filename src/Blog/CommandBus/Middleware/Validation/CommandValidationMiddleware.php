<?php

namespace Blog\CommandBus\Middleware\Validation;

use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommandValidationMiddleware implements MessageBusMiddleware
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function handle($message, callable $next)
    {
        if (is_object($message) && method_exists($message, 'loadValidatorMetadata')) {
            $this->validate($message);
        }

        $next($message);
    }

    private function validate($message)
    {
        /** @var ConstraintViolationList $violations */
        $violations = $this->validator->validate($message);

        if (count($violations) != 0) {
            $errors = [];

            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            throw new CommandValidationException($errors);
        }
    }
}
