<?php

/*
 * This file is part of the RPSContactBundle package.
 *
 * (c) Yos Okusanya <yos.okus@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RPS\ContactBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use RPS\ContactBundle\Model\ContactInterface;
use RPS\ContactBundle\Model\ContactManager as AbstractContactManager;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Default ContactManager ODM.
 */
class ContactManager extends AbstractContactManager
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var Doctrine\ODM\MongoDB\DocumentRepository
     */
    protected $repository;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface   $dispatcher
     * @param \Symfony\Component\Security\Core\SecurityContextInterface     $context
     * @param \Doctrine\ODM\MongoDB\DocumentManager                         $dm
     * @param string                                                        $class
     */
    public function __construct(EventDispatcherInterface $dispatcher, SecurityContextInterface $context, DocumentManager $dm, $class)
    {
        parent::__construct($dispatcher, $context, $dm->getClassMetadata($class)->name);

        $this->dm = $dm;
        $this->repository = $dm->getRepository($class);
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getEntityManager()
    {
        return $this->dm;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentRepository
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
        return !$this->dm->getUnitOfWork()->isInIdentityMap($contact);
    }

    /**
     * {@inheritDoc}
     */
    protected function doSave(ContactInterface $contact)
    {
        $this->dm->persist($contact);
        $this->dm->flush();
    }

    /**
     * {@inheritDoc}
     */
    protected function doRemove(ContactInterface $contact)
    {
        $this->dm->remove($contact);
        $this->dm->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getPaginatedList($offset, $limit, $criteria = array())
    {
        $queryBuilder = $this->dm->createQueryBuilder($this->class);

        // set userId
        if(isset($criteria['userId'])) {
            $queryBuilder->field('userId')->equals((int)$criteria['userId']);
        }

        // set ordering
        $sorting = array();
        if(isset($criteria['order'])) {
            foreach($criteria['order'] as $ordering) {
                $sorting[$ordering['field']] = $ordering['order'];
            }
        } else  {
            $sorting = array('fname' => 'ASC', 'lname' => 'ASC');   //default ordering
        }

        $queryBuilder->sort($sorting);

        if (null === $this->pager) {
            return $queryBuilder->getQuery()->getResult();
        }

        return $this->pager->getList($queryBuilder, $offset, $limit);
    }

    /**
     * {@inheritDoc}
     */
    protected function doDelete($ids)
    {
        $this->repository->createQueryBuilder()
            ->remove()
            ->field('id')->in($ids)
            ->field('userId')->equals($this->getUserId())
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritDoc}
     */
    protected  function doDeleteUser($userId)
    {
        $this->repository->createQueryBuilder()
            ->remove()
            ->field('userId')->equals($userId)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function getImages($ids)
    {
        $queryBuilder = $this->repository->createQueryBuilder()
            ->select("imagePath")
            ->hydrate(false)
            ->field('id')->in($ids)
            ->field('userId')->equals($this->getUserId())
            ->field('imagePath')->exits(true)
            ->field('imagePath')->notIn(array('', null));

        return $queryBuilder->getQuery()->toArray();

        //return iterator_to_array($queryBuilder->getQuery()->execute());
    }

}
