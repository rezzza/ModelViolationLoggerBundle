<?php

namespace Rezzza\ModelViolationLoggerBundle\Entity;

use Rezzza\ModelViolationLoggerBundle\Model\ViolationManagerInterface;
use Rezzza\ModelViolationLoggerBundle\Violation\ViolationList;
use Rezzza\ModelViolationLoggerBundle\Violation\ViolationListComparator;
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
    public function link($model, ViolationList $list)
    {
        $actualViolations = $this->getViolationListNotFixed($model);

        $comparator = ViolationListComparator::compare($actualViolations, $list);

        $objectManager = $this->getObjectManager();

        $change = false;
        foreach ($comparator->removed as $removedViolation) {
            $change = true;

            $removedViolation->setFixed(true);
            $objectManager->persist($removedViolation);
        }

        foreach ($comparator->new as $newViolation) {
            $change = true;
            $objectManager->persist($newViolation);
        }

        $objectManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getViolationListNotFixed($model)
    {
        $list = new ViolationList();

        $violations = $this->getObjectManager()
            ->getRepository($this->violationClass)
            ->createQueryBuilder('v')
            ->where('v.fixed = :fixed')
            ->andWhere('v.subjectModel = :subjectModel')
            ->andWhere('v.subjectId = :subjectId')
            ->setParameter('fixed', false)
            ->setParameter('subjectModel', $this->getClassForModel($model))
            ->setParameter('subjectId', $model->getId())
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
