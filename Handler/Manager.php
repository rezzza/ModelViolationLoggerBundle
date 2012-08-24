<?php

namespace Rezzza\ModelViolationLoggerBundle\Handler;

/**
 * Manager
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Manager
{
    /**
     * @var array
     */
    private $handler = array();

    /**
     * @param ViolationHandlerInterface $handler handler
     */
    public function add(ViolationHandlerInterface $handler)
    {
        $this->handlers[$handler->getModel()] = $handler;
    }

    /**
     * @param string $model model
     *
     * @return ViolationHandlerInterface
     */
    public function fetch($model)
    {
        foreach ($this->handlers as $handlerModel => $handler) {
            if ($model instanceof $handlerModel) {
                return $handler;
            }
        }
    }
}
