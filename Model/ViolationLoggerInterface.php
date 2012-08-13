<?php

namespace Rezzza\ModelViolationLoggerBundle\Model;

/**
 * lViolationLoggerInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface ViolationLoggerInterface
{
    /**
     * @return ViolationLoggerInterface
     */
    public function getViolationLogger();
}
