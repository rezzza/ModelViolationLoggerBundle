<?php

namespace Rezzza\ModelViolationLoggerBundle\Tests\Units\Model;

use mageekguy\atoum;

use Rezzza\ModelViolationLoggerBundle\Model\Violation as TestedClass;

class Violation extends atoum\test
{

    public function dataProviderEquals()
    {
        return array(
            // same violations
            array(
                $this->generateViolation('stdClass', 11, null, 111, 'Something wrong...', array('a'=>'b'), false, new \DateTime('2000-01-01 01:01:01')),
                $this->generateViolation('stdClass', 11, null, 111, 'Something wrong...', array('a'=>'b'), false, new \DateTime('2000-01-01 01:01:01')),
                true,
            ),

            // same violations (but some non identifier attribute with different value)
            array(
                $this->generateViolation('stdClass', 11, null, 111, 'Something wrong...', array('a'=>'b'), false, new \DateTime('2000-01-01 01:01:01')),
                $this->generateViolation('stdClass', 11, null, 111, 'Something wrong...', array('a'=>'b'), true,  new \DateTime('1972-02-20 20:20:20')),
                true,
            ),

            // not the same violations (different subject model)
            array(
                $this->generateViolation('stdClass',  11, null, 111, 'Something wrong...', array('a'=>'b'), false, new \DateTime('2000-01-01 01:01:01')),
                $this->generateViolation('toto\tata', 11, null, 111, 'Something wrong...', array('a'=>'b'), false, new \DateTime('2000-01-01 01:01:01')),
                false,
            ),

            // not the same violations (different subject id)
            array(
                $this->generateViolation('stdClass', 11, null, 111, 'Something wrong...', array('a'=>'b'), false, new \DateTime('2000-01-01 01:01:01')),
                $this->generateViolation('stdClass', 42, null, 111, 'Something wrong...', array('a'=>'b'), false, new \DateTime('2000-01-01 01:01:01')),
                false,
            ),

            // not the same violations (different subject property)
            array(
                $this->generateViolation('stdClass', 11, null,             111, 'Something wrong...',         array('a'=>'b'), false, new \DateTime('2000-01-01 01:01:01')),
                $this->generateViolation('stdClass', 11, 'iAmAnAttribute', 111, 'Another something wrong...', array('a'=>'b'), false, new \DateTime('2000-01-01 01:01:01')),
                false,
            ),

            // not the same violations (different code)
            array(
                $this->generateViolation('stdClass', 11, null, 111, 'Something wrong...', array('a'=>'b'), false, new \DateTime('2000-01-01 01:01:01')),
                $this->generateViolation('stdClass', 11, null, 666, 'Something wrong...', array('a'=>'b'), false, new \DateTime('2000-01-01 01:01:01')),
                false,
            ),

            // not the same violations (different message)
            array(
                $this->generateViolation('stdClass', 11, null, 111, 'Something wrong...',         array('a'=>'b'), true, new \DateTime('2000-01-01 01:01:01')),
                $this->generateViolation('stdClass', 11, null, 111, 'Another something wrong...', array('a'=>'b'), true, new \DateTime('2000-01-01 01:01:01')),
                false,
            ),

            // not the same violations (different message params)
            array(
                $this->generateViolation('stdClass', 11, null, 111, 'Something wrong...', array('a'=>'b'), true, new \DateTime('2000-01-01 01:01:01')),
                $this->generateViolation('stdClass', 11, null, 111, 'Something wrong...', array(),         true, new \DateTime('2000-01-01 01:01:01')),
                false,
            ),
        );
    }

    /**
     * @dataProvider dataProviderEquals
     */
    public function test_it_should_consider_equals_these_violation($violationA, $violationB, $expectedResult)
    {
        $this
            ->boolean($violationA->equals($violationB))
            ->isIdenticalTo($expectedResult);

        $this
            ->boolean($violationB->equals($violationA))
            ->isIdenticalTo($expectedResult);
    }

    /**
     * @param $subjectModel string
     * @param $subjectId integer
     * @param $subjectProperty string
     * @param $code integer
     * @param $message string
     * @param $messageParameters array
     * @param $isFixed boolean
     * @param $createdAt \DateTime
     * @return Violation
     */
    private function generateViolation($subjectModel, $subjectId, $subjectProperty, $code, $message, array $messageParameters, $isFixed, \DateTime $createdAt)
    {
        $violation = new TestedClass();

        $violation->setSubjectModel($subjectModel);
        $violation->setSubjectId($subjectId);
        $violation->setSubjectProperty($subjectProperty);
        $violation->setCode($code);
        $violation->setMessage($message);
        $violation->setMessageParameters($messageParameters);
        $violation->setFixed($isFixed);
        $violation->setCreatedAt($createdAt);

        return $violation;
    }
} 
