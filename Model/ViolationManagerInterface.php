<?php

namespace Rezzza\ModelViolationLoggerBundle\Model;

use Rezzza\ModelViolationLoggerBundle\Violation\ViolationList;

interface ViolationManagerInterface
{
    /**
     * @param object        $model model
     * @param ViolationList $list  list
     */
    public function link($model, ViolationList $list);

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
