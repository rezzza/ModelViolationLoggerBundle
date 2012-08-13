<?php

namespace Rezzza\ModelViolationLoggerBundle\Violation;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * AbstractViolationLogger
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
abstract class AbstractViolationLogger
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
