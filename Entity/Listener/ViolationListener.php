<?php

namespace Rezzza\ModelViolationLoggerBundle\Entity\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use Doctrine\ORM\Version as ORMVersion;
use Rezzza\ModelViolationLoggerBundle\Model\ViolationManagerInterface;
use Rezzza\ModelViolationLoggerBundle\Handler\Manager as HandlerManager;
use Rezzza\ModelViolationLoggerBundle\Violation\ViolationList;

/**
 * ViolationListener
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class ViolationListener
{
    /**
     * @var ViolationManagerInterface
     */
    private $violationManager;

    /**
     * @var HandlerManager
     */
    private $handlerManager;

    /**
     * @param ViolationManagerInterface $violationManager violationManager
     * @param HandlerManager            $manager          manager
     */
    public function __construct(ViolationManagerInterface $violationManager, HandlerManager $manager)
    {
        $this->violationManager = $violationManager;
        $this->handlerManager   = $manager;
    }

    /**
     * @param LifecycleEventArgs $args args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $this->computeViolations($args->getEntity(), $em, $uow);
    }

    /**
     * @param LifecycleEventArgs $args args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $this->computeViolations($args->getEntity(), $em, $uow);
    }

    /**
     * @param object        $entity        entity
     * @param ObjectManager $entityManager entityManager
     * @param UnitOfWork    $uow           uow
     */
    private function computeViolations($entity, ObjectManager $entityManager, UnitOfWork $uow)
    {
        if (!$handlers = $this->handlerManager->fetch($entity)) {
            return null;
        }

        $list = $this->violationManager->getViolationListNotFixed($entity);
        $list->setFixed(true);

        foreach ($handlers as $handler) {
            $handler->validate($entity, $list);
        }

        $uow->computeChangeSets();

        foreach ($list as $violation) {
            $changeSet = $uow->getEntityChangeSet($violation);
            if (!empty($changeSet)) {
                $entityManager->persist($violation);
            }
        }

        $entityManager->flush();
    }
}
