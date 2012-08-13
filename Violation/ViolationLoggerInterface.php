<?php

namespace Rezzza\ModelViolationLoggerBundle\Violation;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Rezzza\ModelViolationLoggerBundle\Model\ViolationLoggerInterface as ModelViolationLoggerInterface;

/**
 * ViolationLoggerInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface ViolationLoggerInterface
{
    /**
     * @param ContainerInterface $container container
     */
    public function setContainer(ContainerInterface $container);

    /**
     * @param ModelViolationLoggerInterface $object        object
     * @param ViolationList                 $violationList violationList
     */
    public function validate(ModelViolationLoggerInterface $object, ViolationList $violationList);
}
