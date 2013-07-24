<?php

namespace Rezzza\ModelViolationLoggerBundle\Tests\Units\Handler;

require_once __DIR__ . '/../../../vendor/autoload.php';

use mageekguy\atoum;
use Rezzza\ModelViolationLoggerBundle\Handler\Manager as ManagerModel;

/**
 * Manager
 *
 * @uses atoum\test
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Manager extends atoum\test
{
    /**
     * @param null|atoum\factory $factory factory
     */
    public function __construct(atoum\factory $factory = null)
    {
       $this->setTestNamespace('Tests\Units');
       parent::__construct($factory);
    }

    public function testFetch()
    {
        $this->mockClass('Rezzza\ModelViolationLoggerBundle\Handler\ViolationHandlerInterface', '\Mock');

        $createHandler = function($v) {
            $handler = new \mock\ViolationHandlerInterface();
            $handler ->getMockController()->getModel = $v;

            return $handler;
        };

        $handler1 = $createHandler('\stdClass');
        $handler2 = $createHandler('\stdClass');
        $handler3 = $createHandler('\stdClass');
        $handler4 = $createHandler('\stdClass');
        $handler5 = $createHandler('\ReflectionClass');

        $manager = new ManagerModel();
        $manager->add($handler1, 3);
        $manager->add($handler2, 2);
        $manager->add($handler3, 4);
        $manager->add($handler4, 1);
        $manager->add($handler5, 6);

        $handlers = $manager->fetch(new \stdClass());

        $this->integer(count($handlers))
            ->isEqualTo(4)
            ->object($handlers[0])
            ->isIdenticalTo($handler4)
            ->object($handlers[1])
            ->isIdenticalTo($handler2)
            ->object($handlers[2])
            ->isIdenticalTo($handler1)
            ->object($handlers[3])
            ->isIdenticalTo($handler3)
            ;
    }
}
