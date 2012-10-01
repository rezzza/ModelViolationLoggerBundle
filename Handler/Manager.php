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
     * @param ViolationHandlerInterface $handler  handler
     * @param integer|null              $priority
     */
    public function add(ViolationHandlerInterface $handler, $priority = null)
    {
        $this->handlers[$handler->getModel()][] = array(
            'priority' => $priority,
            'handler'  => $handler,
        );
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
                return $this->fetchHandlersByPriority($this->handlers[$handlerModel]);
            }
        }
    }

    /**
     * @param array $handlers handlers
     *
     * @return array
     */
    protected function fetchHandlersByPriority(array $handlers)
    {
        usort($handlers, function($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return 0;
            }

            return $a['priority'] < $b['priority'] ? -1 : 1;
        });

        $result = array();
        foreach ($handlers as $handler) {
            $result[] = $handler['handler'];
        }

        return $result;
    }
}
