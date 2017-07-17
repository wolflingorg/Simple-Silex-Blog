<?php

namespace Blog\Service\SearchEngine;

use Blog\Exception\ValidationException;
use Blog\Service\Repository\RepositoryManager;
use Blog\Service\SearchEngine\Interfaces\CriteriaInterface;
use Blog\Service\SearchEngine\Interfaces\ResultInterface;
use Blog\Service\SearchEngine\Interfaces\SearchEngineInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SearchEngine implements SearchEngineInterface
{
    private $rm;

    private $validator;

    public function __construct(RepositoryManager $rm, ValidatorInterface $validator)
    {
        $this->rm = $rm;
        $this->validator = $validator;
    }

    public function match(CriteriaInterface $criteria): ResultInterface
    {
        $this->validate($criteria->getFiltering());

        $repo = $this->rm->get($criteria->getResourceName());
        $result = $repo->findByCriteria($criteria);

        return new SearchResult($result);
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

            throw new ValidationException($errors);
        }
    }
}
