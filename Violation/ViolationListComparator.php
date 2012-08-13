<?php
namespace Rezzza\ModelViolationLoggerBundle\Violation;

/**
 * ViolationListComparator
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class ViolationListComparator
{
    public $removed = array();
    public $unchanged = array();
    public $new = array();

    /**
     * @param ViolationList $oldList oldList
     * @param ViolationList $newList newList
     *
     * @return ViolationListComparator
     */
    public static function compare(ViolationList $oldList, ViolationList $newList)
    {
        $comparator = new self();

        foreach ($oldList as $key => $oldViolation) {

            $contains = $newList->contains($oldViolation);
            if (false === $contains) {
                $comparator->removed[] = $oldViolation;
            } else {
                $comparator->unchanged[] = $oldViolation;
                $newList->remove($contains);
            }
            unset($key);
        }

        foreach ($newList as $newViolation) {
            $comparator->new[] = $newViolation;
        }

        return $comparator;
    }
}
