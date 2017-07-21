<?php

namespace Blog\Service;

use Blog\Exception\ValidationException;
use Blog\Repository\Interfaces\CriteriaInterface;
use Blog\Repository\Interfaces\CriteriaValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Validates Search Criteria
 *
 * @package Blog\Service
 */
class CriteriaValidator implements CriteriaValidatorInterface
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
     * @inheritdoc
     */
    public function validate(CriteriaInterface $criteria)
    {
        $violations = new ConstraintViolationList();

        foreach ($criteria->getValidationRules() as $rule) {
            if (!is_array($rule) || count($rule) !== 2) {
                throw new \InvalidArgumentException('Criteria Validation Rule should be an array');
            }

            $violations->addAll($this->validator->validate($rule[0], $rule[1]));
        }

        if ($violations->count() != 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            throw new ValidationException($errors);
        }
    }
}
