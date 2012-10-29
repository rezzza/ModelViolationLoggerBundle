<?php

namespace Rezzza\ModelViolationLoggerBundle\Model;

use Rezzza\ModelViolationLoggerBundle\Model\Violation;
use Rezzza\ModelViolationLoggerBundle\Violation\ViolationList;

interface ViolationManagerInterface
{
    /**
     * @param  Violation $violation violation
     * @return void
     */
    public function updateViolation(Violation $violation);

    /**
     * @param object $model model
     *
     * @return ViolationList
     */
    public function getViolationListNotFixed($model);

    /**
     * @param object $model model
     *
     * @return string
     */
    public function getClassForModel($model);
}
