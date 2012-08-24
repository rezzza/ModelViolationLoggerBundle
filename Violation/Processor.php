<?php

namespace Rezzza\ModelViolationLoggerBundle\Violation;

use Rezzza\ModelViolationLoggerBundle\Model\ViolationManagerInterface;
use Rezzza\ModelViolationLoggerBundle\Handler\Manager as HandlerManager;

/**
 * Processor
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Processor
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
     * @param object $model model
     */
    public function process($model)
    {
        if (!is_object($model)) {
            throw new \InvalidArgumentException('Processor only accept objects');
        }

        $handler = $this->handlerManager->fetch($model);
        if (!$handler) {
            return;
        }

        $list = new ViolationList();

        $handler->validate($model, $list);

        foreach ($list as $violation) {
            $violation->setSubjectModel($this->violationManager->getClassForModel($model));
            $violation->setSubjectId($model->getId()); // actually just support that
        }

        return $this->violationManager->link($model, $list);
    }
}
