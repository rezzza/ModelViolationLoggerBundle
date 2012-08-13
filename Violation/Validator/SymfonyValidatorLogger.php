<?php

namespace Rezzza\ModelViolationLoggerBundle\Violation\Validator;

use Rezzza\ModelViolationLoggerBundle\Violation;
use Rezzza\ModelViolationLoggerBundle\Violation\AbstractViolationLogger;
use Rezzza\ModelViolationLoggerBundle\Violation\ViolationLoggerInterface;
use Rezzza\ModelViolationLoggerBundle\Model\ViolationLoggerInterface as ModelViolationLoggerInterface;

/**
 * SymfonyValidatorLogger
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class SymfonyValidatorLogger extends AbstractViolationLogger implements ViolationLoggerInterface
{
    /**
     * @var string
     */
    protected $model;

    /**
     * @param string $model model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @{inheritdoc}
     */
    public function validate(ModelViolationLoggerInterface $object, Violation\ViolationList $violationList)
    {
        $errors = $this->container
            ->get('validator')
            ->validate($object);

        foreach ($errors as $error) {
            $constraint = call_user_func_array(array($this->model, 'createFromConstraintViolation'), array($error));
            $violationList->add($constraint);
        }

        return $violationList;
    }

}
