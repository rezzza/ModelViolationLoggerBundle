<?php

namespace Rezzza\ModelViolationLoggerBundle\Handler;

use Symfony\Component\Validator\Validator;
use Rezzza\ModelViolationLoggerBundle\Violation;

/**
 * SymfonyValidatorHandler
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class SymfonyValidatorHandler implements ViolationHandlerInterface
{
    /**
     * @var string
     */
    protected $model;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param string $model model
     */
    public function __construct($model, Validator $validator)
    {
        $this->model = $model;
        $this->validator = $validator;
    }

    /**
     * @{inheritdoc}
     */
    public function validate($object, Violation\ViolationList $violationList)
    {
        $errors = $this->validator->validate($object);

        foreach ($errors as $error) {
            $constraint = call_user_func_array(array($this->model, 'createFromConstraintViolation'), array($error));
            $violationList->add($constraint);
        }

        return $violationList;
    }

    public function getModel()
    {
        return null;
    }
}
