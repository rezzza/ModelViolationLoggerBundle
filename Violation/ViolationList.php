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
     * @param mixed $index index
     */
    public function remove($index)
    {
        if (isset($this->violations[$index])) {
            unset($this->violations[$index]);
        }
    }

}
