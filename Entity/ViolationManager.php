<?php

namespace Rezzza\ModelViolationLoggerBundle\Entity;

use Rezzza\ModelViolationLoggerBundle\Model\ViolationManagerInterface;
use Rezzza\ModelViolationLoggerBundle\Model\Violation;
use Rezzza\ModelViolationLoggerBundle\Violation\ViolationList;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ViolationManager implements ViolationManagerInterface
{
    /**
     * @var string
     */
    protected $violationClass;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container      = $container;
        $this->violationClass = $container->getParameter('rezzza.violation_class');
    }

    /**
     * {@inheritdoc}
     */
    public function updateViolation(Violation $violation)
    {
        $objectManager = $this->getObjectManager();
        $objectManager->persist($violation);
        $objectManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getViolationListNotFixed($model)
    {
        $modelClass = $this->getClassForModel($model);
        $modelId    = $model->getId();

        $list = new ViolationList($modelClass, $modelId);

        $violations = $this->getObjectManager()
            ->getRepository($this->violationClass)
            ->createQueryBuilder('v')
            ->where('v.fixed = :fixed')
            ->andWhere('v.subjectModel = :subjectModel')
            ->andWhere('v.subjectId = :subjectId')
            ->setParameter('fixed', false)
            ->setParameter('subjectModel', $modelClass)
            ->setParameter('subjectId', $modelId)
            ->orderBy('v.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        foreach ($violations as $violation) {
            $list->add($violation);
        }

        return $list;
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * {@inheritdoc}
     */
    public function getClassForModel($model)
    {
        if ($model instanceof \Doctrine\ORM\Proxy\Proxy) {
            $refl = new \ReflectionClass($model);

            return $refl->getParentClass()->getName();
        }

        return get_class($model);
    }
}
