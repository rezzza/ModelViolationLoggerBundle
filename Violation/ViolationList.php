<?php

namespace Rezzza\ModelViolationLoggerBundle\Violation;

use Rezzza\ModelViolationLoggerBundle\Model\Violation;
use \IteratorAggregate;
use \ArrayIterator;
use \Countable;

class ViolationList implements IteratorAggregate, Countable
{
    /**
     * @var array
     */
    protected $violations = array();

    /**
     * @var string
     */
    public $subjectModel;

    /**
     * @var integer
     */
    public $subjectId;

    /**
     * @param string             $subjectModel       subjectModel
     * @param integer            $subjectId          subjectId
     * @param null|ViolationList $existingViolations existingViolations
     */
    public function __construct($subjectModel, $subjectId, ViolationList $existingViolations = null)
    {
        $this->subjectModel = $subjectModel;
        $this->subjectId    = $subjectId;

        if ($existingViolations) {

            $this->violations = $existingViolations->getIterator()
                ->getArrayCopy();
        }
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->violations);
    }

    /**
     * @return integer
     */
    public function count()
    {
        return count($this->violations);
    }

    /**
     * @param Violation $violation violation
     */
    public function add(Violation $violation)
    {
        $violation->setSubjectModel($this->subjectModel);
        $violation->setSubjectId($this->subjectId);
        $violation->setFixed(false);

        if (!$violation->getId() && false !== $key = $this->contains($violation)) {
            $this->violations[$key]->setFixed(false);

            return;
        }

        $this->violations[] = $violation;
    }

    /**
     * @param Violation $violation violation
     *
     * @return integer|false
     */
    public function contains(Violation $violation)
    {
        $checkViolationSerialized = serialize($violation);

        foreach ($this->violations as $key => $violation) {
            if (serialize($violation) === $checkViolationSerialized) {
                return $key;
            }
        }

        return false;
    }

    /**
     * @param boolean $fixed fixed
     */
    public function setFixed($fixed)
    {
        foreach ($this->violations as $violation) {
            $violation->setFixed($fixed);
        }
    }
}
