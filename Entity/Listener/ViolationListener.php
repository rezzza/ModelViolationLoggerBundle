<?php

namespace Rezzza\ModelViolationLoggerBundle\Entity\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Rezzza\ModelViolationLoggerBundle\Model\ViolationManagerInterface;
use Rezzza\ModelViolationLoggerBundle\Handler\Manager as HandlerManager;
use Rezzza\ModelViolationLoggerBundle\Violation\ViolationList;

/**
 * ViolationListener
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class ViolationListener
{
    /**
     * @var ViolationManagerInterface
     */
    private $violationManager;

    /**
     * @var HandlerManager
     */
    private $handlerManager;

    /**
     * @param ViolationManagerInterface $violationManager violationManager
     * @param HandlerManager            $manager          manager
     */
    public function __construct(ViolationManagerInterface $violationManager, HandlerManager $manager)
    {
        $this->violationManager = $violationManager;
        $this->handlerManager   = $manager;
    }

    /**
     * @param LifecycleEventArgs $args args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->process($args);
    }

    /**
     * @param LifecycleEventArgs $args args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->process($args);
    }

    /**
     * @param LifecycleEventArgs $args args
     */
    protected function process(LifecycleEventArgs $args)
    {
        $model = $args->getEntity();

        if (!is_object($model)) {
            throw new \InvalidArgumentException('Processor only accept objects');
        }

        $handlers = $this->handlerManager->fetch($model);
        if (!$handlers) {
            return;
        }

        $existing     = $this->violationManager->getViolationListNotFixed($model);

        foreach ($existing as $violation) {
            $violation->setFixed(true); // wait to be unfixed if reappear
        }

        $subjectModel = $this->violationManager->getClassForModel($model);
        $subjectId    = $model->getId(); // actually just support that
        $list         = new ViolationList($subjectModel, $subjectId, $existing);

        foreach ($handlers as $handler) {
            $handler->validate($model, $list);
        }

        if (count($list) === 0) {
            return;
        }

        $this->violationManager->updateViolationList($list);
    }
}
