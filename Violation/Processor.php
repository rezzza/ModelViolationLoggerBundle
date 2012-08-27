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

        foreach ($list as $violation) {
            $this->violationManager->updateViolation($violation);
        }
    }
}
