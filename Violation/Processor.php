<?php

namespace Rezzza\ModelViolationLoggerBundle\Violation;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Rezzza\ModelViolationLoggerBundle\Model\ViolationLoggerInterface as ModelViolationLoggerInterface;
use Rezzza\ModelViolationLoggerBundle\Model\ViolationManagerInterface;

/**
 * Processor
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Processor
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ViolationManagerInterface
     */
    private $violationManager;

    /**
     * @param ContainerInterface $container container
     */
    public function __construct(ContainerInterface $container, ViolationManagerInterface $violationManager)
    {
        $this->container        = $container;
        $this->violationManager = $violationManager;
    }

    /**
     * @param ModelViolationLoggerInterface $model model
     */
    public function process(ModelViolationLoggerInterface $model)
    {
        $logger = $model->getViolationLogger();
        if (!$logger instanceof ViolationLoggerInterface) {
            if (!is_object($logger)) {
                throw new \RuntimeException('getViolationLogger() must return an object which implements interface "ViolationLoggerInterface"');
            } else {
                throw new \RuntimeException(sprintf('Violation class "%s" must implements interface "ViolationLoggerInterface"', get_class($violation)));
            }
        }

        $list = new ViolationList();

        $logger->setContainer($this->container);
        $logger->validate($model, $list);

        foreach ($list as $violation) {
            $violation->setSubjectModel($this->violationManager->getClassForModel($model));
            $violation->setSubjectId($model->getId()); // actually just support that
        }

        $this->violationManager->setContainer($this->container);

        return $this->violationManager->link($model, $list);
    }
}
