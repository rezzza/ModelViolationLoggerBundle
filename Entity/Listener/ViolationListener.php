<?php

namespace Rezzza\ModelViolationLoggerBundle\Entity\Listener;

use Rezzza\ModelViolationLoggerBundle\Violation\Processor;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * ViolationListener
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class ViolationListener
{
    /**
     * @var Processor
     */
    private $processor;

    /**
     * @param Processor $processor processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor      = $processor;
    }

    /**
     * @param LifecycleEventArgs $args args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->processor->process($args->getEntity());
    }

    /**
     * @param LifecycleEventArgs $args args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->processor->process($args->getEntity());
    }
}
