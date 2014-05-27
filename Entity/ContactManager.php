<?php

/*
 * This file is part of the RPSContactBundle package.
 *
 * (c) Yos Okusanya <yos.okusanya@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RPS\ContactBundle\Entity;

use Doctrine\ORM\EntityManager;
use RPS\ContactBundle\Model\ContactInterface;
use RPS\ContactBundle\Model\ContactManager as AbstractContactManager;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Default ContactManager ORM.
 */
class ContactManager extends AbstractContactManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface   $dispatcher
     * @param \Symfony\Component\Security\Core\SecurityContextInterface     $context
     * @param \Doctrine\ORM\EntityManager                                   $em
     * @param string                                                        $class
     */
    public function __construct(EventDispatcherInterface $dispatcher, SecurityContextInterface $context, EntityManager $em, $class)
    {
        parent::__construct($dispatcher, $context, $em->getClassMetadata($class)->name);

        $this->em = $em;
        $this->repository = $em->getRepository($class);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * {@inheritDoc}
     */
    public function findContactBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findContactsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function isNew(ContactInterface $contact)
    {
        return !$this->em->getUnitOfWork()->isInIdentityMap($contact);
    }

    /**
     * {@inheritDoc}
     */
    public function getPaginatedList($offset, $limit, $criteria = array())
    {
        $queryBuilder = $this->repository->createQueryBuilder('c');

        // set userId
        if(isset($criteria['userId'])) {
            $queryBuilder->andWhere('c.userId = :userId')
                ->setParameter('userId', $criteria['userId']);
        }

        // set ordering
        if(isset($criteria['order'])) {
            foreach($criteria['order'] as $ordering) {
                $queryBuilder->addOrderBy($ordering['field'], $ordering['order']);
            }
        } else  {
            $queryBuilder->orderBy('c.firstName, c.lastName', 'ASC');  //default ordering
        }

        if (null === $this->pager) {
            return $queryBuilder->getQuery()->getResult();
        }

        return $this->pager->getList($queryBuilder, $offset, $limit);
    }

    /**
     * {@inheritDoc}
     */
    protected function doSave(ContactInterface $contact)
    {
        $this->em->persist($contact);
        $this->em->flush();
    }

    /**
     * {@inheritDoc}
     */
    protected function doRemove(ContactInterface $contact)
    {
        $this->em->remove($contact);
        $this->em->flush();
    }

    /**
     * {@inheritDoc}
     */
    protected function doDelete($ids)
    {
        $userId = $this->getUserId();

        $queryBuilder = $this->em->createQueryBuilder()
            ->delete($this->getClass(), 'c')
            ->where('c.id IN (:ids)')
            ->andWhere('c.userId = :userId')
            ->setParameters( array('userId' => $userId, 'ids' => $ids,));

        $queryBuilder->getQuery()->execute();
    }

    /**
     * {@inheritDoc}
     */
    protected function doDeleteUser($userId)
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->delete($this->getClass(), 'c')
            ->where('c.userId = :userId')
            ->setParameter( 'userId', $userId);

        $queryBuilder->getQuery()->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function getImages($ids)
    {
        $queryBuilder = $this->repository->createQueryBuilder('c')
            ->select('c.imagePath')
            ->where("c.userId = :userId")
            ->andWhere("c.imagePath != ''")
            ->andWhere('c.id IN (:ids)')
            ->setParameters( array(
                'userId' => $this->getUserId(),
                'ids' => $ids,
            ));

        return $queryBuilder->getQuery()->getArrayResult();
    }

}
