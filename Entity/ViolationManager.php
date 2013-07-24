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
    public function updateViolationList(ViolationList $violationList)
    {
        if (count($violationList) === 0) {
            return;
        }

        $objectManager = $this->getObjectManager();

        foreach ($violationList as $violation) {
            $objectManager->persist($violation);
        }

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
     * {@inheritdoc}
     */
    public function getViolations(array $params = array())
    {
        $order = isset($params['order']) && in_array(strtoupper($params['order']), array('ASC', 'DESC')) ? $params['order'] : 'DESC';

        $queryBuilder = $this
            ->getObjectManager()
            ->getRepository($this->violationClass)
            ->createQueryBuilder('v')
            ->orderBy('v.createdAt', $order)
        ;

        if (isset($params['subject'])) {
            $subject = $params['subject'];
            $params['subject_model'] = $this->getClassForModel($subject);
            $params['subject_id']    = $subject->getId();
        }

        if (isset($params['subject_model'])) {
            $queryBuilder
                ->andWhere('v.subjectModel = :subjectModel')
                ->setParameter('subjectModel', $params['subject_model'])
            ;
        }

        if (isset($params['subject_id'])) {
            if (is_array($params['subject_id'])) {
                if (isset($params['subject_model'])) {
                    if (!empty($params['subject_id'])) {
                        $queryBuilder
                            ->andWhere('v.subjectId IN (:subjectId)')
                            ->setParameter('subjectId', $params['subject_id'])
                        ;
                    } else {
                        $queryBuilder->andWhere('v.code IS NULL');
                    }
                }
            } else {
                $queryBuilder
                    ->andWhere('v.subjectId = :subjectId')
                    ->setParameter('subjectId', $params['subject_id'])
                ;
            }
        }

        if (isset($params['codes'])) {
            $codes = $params['codes'];
            if (!is_array($codes)) {
                $codes = array($codes);
            }
            if (!empty($codes)) {
                $queryBuilder
                    ->andWhere('v.code IN (:codes)')
                    ->setParameter('codes', $codes)
                ;
            } else {
                $queryBuilder->andWhere('v.code IS NULL');
            }
        }

        if (isset($params['is_fixed']) && is_bool($params['is_fixed'])) {
            $queryBuilder
                ->andWhere('v.fixed = :fixed')
                ->setParameter('fixed', $params['is_fixed'])
            ;
        }

        if (isset($params['is_notified'])) {
            if (true == $params['is_notified']) {
                $queryBuilder->andWhere('v.notifiedAt IS NOT NULL');
            } else {
                $queryBuilder->andWhere('v.notifiedAt IS NULL');
            }
        }

        return $queryBuilder->getQuery()->getResult();
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
