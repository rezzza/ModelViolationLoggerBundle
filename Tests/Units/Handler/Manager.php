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
     * @param atoum\factory $factory factory
     */
    public function __construct(atoum\factory $factory = null)
    {
       $this->setTestNamespace('Tests\Units');
       parent::__construct($factory);
    }

    public function testFetch()
    {
        $this->mockClass('Rezzza\ModelViolationLoggerBundle\Handler\ViolationHandlerInterface', '\Mock');

        $handler1 = new \Mock\ViolationHandlerInterface();
        $handler1->getMockController()->getModel = '\stdClass';

        $handler2 = clone $handler1;
        $handler3 = clone $handler1;
        $handler4 = clone $handler1;

        $handler5 = new \Mock\ViolationHandlerInterface();
        $handler5->getMockController()->getModel = '\ReflectionClass';

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
