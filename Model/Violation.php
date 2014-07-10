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
     * @var string
     */
    protected $subjectModel;

    /**
     * @var integer
     */
    protected $subjectId;

    /**
     * @var string
     */
    protected $subjectProperty;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $messageParameters = array();

    /**
     * @var boolean
     */
    protected $fixed = false;

    /**
     * @var \DateTime
     */
    protected $notifiedAt;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
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
     * @return boolean
     */
    public function isNew()
    {
        return null === $this->id;
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
        $instance->setSubjectProperty($constraint->getPropertyPath());
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
        $this->subjectModel = (string) $subjectModel;

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
     * @param string $subjectProperty
     */
    public function setSubjectProperty($subjectProperty)
    {
        $this->subjectProperty = (string) $subjectProperty;
    }

    /**
     * @return string
     */
    public function getSubjectProperty()
    {
        return $this->subjectProperty;
    }

    /**
     * @param  string    $code
     * @return Violation
     */
    public function setCode($code)
    {
        $this->code = (string) $code;

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
        $this->message = (string) $message;

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
     * @param  \DateTime $notifiedAt
     * @return Violation
     */
    public function setNotifiedAt(\DateTime $notifiedAt)
    {
        $this->notifiedAt = $notifiedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getNotifiedAt()
    {
        return $this->notifiedAt;
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
     * @param Violation $violation
     * @return bool
     */
    public function equals(Violation $violation)
    {
        return (serialize($this) === serialize($violation));
    }

    /**
     * @return array<string>
     */
    public function __sleep()
    {
        return array(
            'subjectModel',
            'subjectId',
            'subjectProperty',
            'code',
            'message',
            'messageParameters',
        );
    }
}
