<?php

namespace Rezzza\ModelViolationLoggerBundle\Tests\Units\Violation;

use mageekguy\atoum;

use Rezzza\ModelViolationLoggerBundle\Model\Violation;
use Rezzza\ModelViolationLoggerBundle\Violation\ViolationList as TestedClass;

class ViolationList extends atoum\test
{
    /**
     * @dataProvider diffData
     */
    public function test_it_should_generate_diff_of_two_violations_list(array $violationsA, array $violationsB, array $results)
    {
        $this->given(
            $violationListA = $this->generateViolationListFromDataProvider('a', 1, $violationsA),
            $violationListB= $this->generateViolationListFromDataProvider('a', 1, $violationsB)
        )
        ->then(
            $diffViolationList = $violationListA->diff($violationListB)
        )

        ->object($diffViolationList)
        ->isInstanceOf('\Rezzza\ModelViolationLoggerBundle\Violation\ViolationList')
        ->hasSize(count($results))

        ->array($diffViolationList->getIterator()->getArrayCopy())
        ->containsValues($results);
    }

    public function diffData()
    {
        // Same violation as $violation2
        $violation1 = $this->generateViolation('stdClass', 42, 1, 'Something is wrong...');

        // Same violation as $violation1
        $violation2 = clone $violation1;

        // An unique violation
        $violation3 = $this->generateViolation('stdClass', 42, 2, 'Another something is wrong...');

        // An other unique violation
        $violation4 = $this->generateViolation('stdClass', 42, 3, 'Well another another something is wrong...');

        return array(

            // violation 2 are in the two list
            array(
                array($violation2, $violation3),
                array($violation2, $violation4),
                array($violation3, $violation4),
            ),

            // violation 1/2 are both in first list and violation2 is in the two list
            array(
                array($violation1, $violation2, $violation3),
                array($violation2, $violation4),
                array($violation3, $violation4),
            ),

            // no violations in common
            array(
                array($violation1, $violation3),
                array($violation4),
                array($violation1, $violation2, $violation3),
            ),


            // no violations in common (but violation1 and 2 are the same).
            array(
                array($violation1, $violation2),
                array($violation3),
                array($violation1, $violation3),
            ),

            // no violations in common, empty violation list
            array(
                array($violation3, $violation4),
                array(),
                array($violation3, $violation4),
            ),

            // no violations in common, empty violation list
            array(
                array(),
                array($violation3, $violation4),
                array($violation3, $violation4),
            ),

            // all violation in common, same instance in both list.
            array(
                array($violation3, $violation4),
                array($violation3, $violation4),
                array(),
            ),

            // all violation in common, same violations in both list.
            array(
                array($violation1, $violation2),
                array($violation1, $violation2),
                array(),
            ),
        );
    }

    public function test_diff_two_list_on_different_subject_should_throw_an_exception()
    {
        $this->given(
            $violationListA = new TestedClass('a', 1),
            $violationListB = new TestedClass('b', 2)
        )->exception(function () use ($violationListA, $violationListB) {
            $violationListA->diff($violationListB);
        })->isInstanceOf('\LogicException');
    }

    private function generateViolationListFromDataProvider($subject, $id, array $violations)
    {
        $violationList = new TestedClass($subject, $id);
        foreach ($violations as $violation) {
            $violationList->add($violation);
        }

        return $violationList;
    }

    private function generateViolation($subject, $id, $code, $message)
    {
        $violation = new Violation();
        $violation->setSubjectId($id);
        $violation->setSubjectModel($subject);
        $violation->setCode($code);
        $violation->setMessage($message);

        return $violation;
    }
}
