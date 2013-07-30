<?php

namespace Rezzza\ModelViolationLoggerBundle\Model;

use Rezzza\ModelViolationLoggerBundle\Model\Violation;
use Rezzza\ModelViolationLoggerBundle\Violation\ViolationList;

interface ViolationManagerInterface
{
    /**
     * @param object $model model
     *
     * @return ViolationList
     */
    public function getViolationListNotFixed($model);

    /**
     * @example $params = array(
     *     'order' => 'ASC',
     *     'subject' => $subject,
     *     'is_fixed' => false,
     *     'is_notified' => true,
     * );
     *
     * @return ViolationList
     */
    public function getViolations(array $params = array());

    /**
     * @param object $model model
     *
     * @return string
     */
    public function getClassForModel($model);
}
