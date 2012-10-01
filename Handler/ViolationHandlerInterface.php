<?php

namespace Rezzza\ModelViolationLoggerBundle\Handler;

use Rezzza\ModelViolationLoggerBundle\Violation\ViolationList;

/**
 * ViolationHandlerInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface ViolationHandlerInterface
{
    /**
     * @param  object        $object        object
     * @param  ViolationList $violationList violationList
     * @return ViolationList
     */
    public function validate($object, ViolationList $violationList);

    /**
     * FQCN class model
     *
     * @return string
     */
    public function getModel();
}
