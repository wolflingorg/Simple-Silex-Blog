<?php

namespace Blog\CommandBus\Middleware\Validation;

use Blog\Exception\ValidationException;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommandValidationMiddleware implements MessageBusMiddleware
{
    private $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param object $message
     * @param callable $next
     */
    public function handle($message, callable $next)
    {
        if (is_object($message) && method_exists($message, 'loadValidatorMetadata')) {
            $this->validate($message);
        }

        $next($message);
    }

    /**
     * @param object $message
     */
    private function validate($message)
    {
        /** @var ConstraintViolationList $violations */
        $violations = $this->validator->validate($message);

        if (count($violations) != 0) {
            $errors = [];

            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            throw new ValidationException($errors);
        }
    }
}
