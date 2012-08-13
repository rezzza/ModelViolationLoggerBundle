<?php

namespace Rezzza\ModelViolationLoggerBundle\Model;

use Rezzza\ModelViolationLoggerBundle\Model\ViolationLoggerInterface as ModelViolationLoggerInterface;
use Rezzza\ModelViolationLoggerBundle\Violation\ViolationList;
use Symfony\Component\DependencyInjection\ContainerInterface;

interface ViolationManagerInterface
{
    /**
     * @param ContainerInterface $container container
     */
    public function setContainer(ContainerInterface $container);

    /**
     * @param ModelViolationLoggerInterface $model model
     * @param ViolationList                 $list  list
     */
    public function link(ModelViolationLoggerInterface $model, ViolationList $list);

    /**
     * @param ModelViolationLoggerInterface $model model
     *
     * @return ViolationList
     */
    public function getViolationListNotFixed(ModelViolationLoggerInterface $model);

    /**
     * @param ModelViolationLoggerInterface $model model
     *
     * @return string
     */
    public function getClassForModel(ModelViolationLoggerInterface $model);
}
