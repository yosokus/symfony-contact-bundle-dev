<?php

/*
 * This file is part of the RPSContactBundle package.
 *
 * (c) Yos Okusanya <yos.okus@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace RPS\ContactBundle\Model;

use Symfony\Component\Form\Form;

/**
 * Interface to be implemented by the contact manager.
 */
interface ContactManagerInterface
{
    /**
     * @param string $id
     *
     * @return ContactInterface
     */
    public function findContactById($id);

    /**
     * Finds a contact by the given criteria
     *
     * @param array $criteria
     *
     * @return ContactInterface
     */
    public function findContactBy(array $criteria);

    /**
     * Finds contacts by the given criteria
     *
     * @param array $criteria
     *
     * @return array of ContactInterface
     */
    public function findContactsBy(array $criteria);

    /**
     * Creates an empty contact instance
     *
     * @param integer $id
     *
     * @return ContactInterface
     */
    public function createContact($id = null);

    /**
     * Saves a contact
     *
     * @param ContactInterface $contact
     */
    public function save(ContactInterface $contact);

    /**
     * Returns the contact fully qualified class name
     *
     * @return string
     */
    public function getClass();

    /**
     * Deletes a contact
     *
     * @param ContactInterface $contact
     */
    public function remove(ContactInterface $contact);

    /**
     * Deletes a list of contacts
     *
     * @param array $ids
     */
    public function delete(array $ids);

    /**
     * Returns the user id
     *
     * @return string
     */
    public function getUserId();

    /**
     * Deletes all contacts belonging to a single user
     *
     * @param integer $userId
     */
    public function deleteUser($userId);

    /**
     * Finds contacts by the given criteria
     * and from the query offset.
     *
     * @param integer   $offset
     * @param integer   $limit
     * @param array     $criteria
     *
     * @return array of ContactInterface
     */
    public function getPaginatedList($offset, $limit, $criteria = array());

    /**
     * Returns the pagination html
     *
     * @return string
     */
    public function getPaginationHtml();

}
