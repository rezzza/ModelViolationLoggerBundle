<?php

namespace Rezzza\ModelViolationLoggerBundle\Violation;

use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\Validator\ConstraintViolationList;
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

    public static function fromConstraintViolationList(ConstraintViolationList $constraintViolations, $model)
    {
        $violationList = new ViolationList(ClassUtils::getClass($model), $model->getId());

        foreach ($constraintViolations as $constraintViolation) {
            $violation = Violation::createFromConstraintViolation($constraintViolation);
            $violation->setSubjectModel(ClassUtils::getClass($model));
            $violation->setSubjectId($model->getId());
            $violationList->add($violation);
        }

        return $violationList;
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
        foreach ($this->violations as $key => $currentViolation) {
            if ($currentViolation->equals($violation)) {
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

    /**
     * @param ViolationList $violationList
     * @return bool
     */
    public function hasSameSubject(ViolationList $violationList)
    {
        return ($violationList->subjectModel === $this->subjectModel
                && $violationList->subjectId === $this->subjectId);
    }

    /**
     * Return the violation only present in th current instance, or in the
     * $violationList parameters.
     *
     * @param ViolationList $violationList
     * @return ViolationList
     * @throws \LogicException
     */
    public function diff(ViolationList $violationList)
    {
        if (!$this->hasSameSubject($violationList)) {
            throw new \LogicException('You can\'t diff ValidationList who haven\'t the same subject.');
        }

        $diffViolationList = new ViolationList($this->subjectModel, $this->subjectId);

        foreach ($this as $violation) {
            if (false === $violationList->contains($violation)) {
                $diffViolationList->add($violation);
            }
        }

        foreach ($violationList as $violation) {
            if (false === $this->contains($violation)) {
                $diffViolationList->add($violation);
            }
        }

        return $diffViolationList;
    }
}
