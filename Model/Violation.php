<?php

namespace Rezzza\ModelViolationLoggerBundle\Model;

use Symfony\Component\Validator\ConstraintViolation;

/**
 * Violation
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Violation
{
    /**
     * @var integer must be overrided
     */
    protected $id;

    /**
     * @var string $subjectModel
     */
    protected $subjectModel;

    /**
     * @var integer $subjectId
     */
    protected $subjectId;

    /**
     * @var string $code
     */
    protected $code;

    /**
     * @var string $message
     */
    protected $message;

    /**
     * @var array $messageParameters
     */
    protected $messageParameters = array();

    /**
     * @var boolean $fixed
     */
    protected $fixed = false;

    /**
     * @var \DateTime $createdAt
     */
    protected $createdAt;

    /**
     * @var \DateTime $updatedAt
     */
    protected $updatedAt;

    /**
     * Init update at
     */
    public function __construct()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @param ConstraintViolation $constraint constraint
     *
     * @return self
     */
    public static function createFromConstraintViolation(ConstraintViolation $constraint)
    {
        $instance = new static();
        $instance->setCode($constraint->getCode());
        $instance->setMessage($constraint->getMessageTemplate());
        $instance->setMessageParameters($constraint->getMessageParameters());
        $instance->setCreatedAt(new \DateTime());

        return $instance;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  string    $subjectModel
     * @return Violation
     */
    public function setSubjectModel($subjectModel)
    {
        $this->subjectModel = $subjectModel;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubjectModel()
    {
        return $this->subjectModel;
    }

    /**
     * @param  integer   $subjectId
     * @return Violation
     */
    public function setSubjectId($subjectId)
    {
        $this->subjectId = (int) $subjectId;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSubjectId()
    {
        return (int) $this->subjectId;
    }

    /**
     * @param  string    $code
     * @return Violation
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param  string    $message
     * @return Violation
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param  array     $messageParameters
     * @return Violation
     */
    public function setMessageParameters(array $messageParameters)
    {
        $this->messageParameters = $messageParameters;

        return $this;
    }

    /**
     * @return array
     */
    public function getMessageParameters()
    {
        return (array) $this->messageParameters;
    }

    /**
     * @param  boolean   $fixed
     * @return Violation
     */
    public function setFixed($fixed)
    {
        $this->fixed = (boolean) $fixed;

        return $this;
    }

    /**
     * Get fixed
     *
     * @return boolean
     */
    public function getFixed()
    {
        return (boolean) $this->fixed;
    }

    /**
     * @param  \DateTime $createdAt
     * @return Violation
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param  \DateTime $updatedAt
     * @return Violation
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return array(
            'subjectModel',
            'subjectId',
            'code',
            'message',
            'messageParameters',
        );
    }
}
