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

use RPS\ContactBundle\Event\Events;
use RPS\ContactBundle\Event\ContactEvent;
use RPS\ContactBundle\Event\ContactStateEvent;
use RPS\ContactBundle\Event\ContactDeleteEvent;
use RPS\ContactBundle\Event\UserDeleteEvent;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Base class for the contact manager.
 */
abstract class ContactManager implements ContactManagerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var SecurityContext
     */
    protected $context;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var PagerInterface
     */
    protected $pager = null;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface   $dispatcher
     * @param \Symfony\Component\Security\Core\SecurityContextInterface     $context
     * @param string                                                        $class
     */
    public function __construct(EventDispatcherInterface $dispatcher, SecurityContextInterface $context, $class)
    {
        $this->dispatcher = $dispatcher;
        $this->context = $context;
        $this->class = $class;
    }

    /**
     * Set pager
     *
     * @param object
     **/
    public function setPager($pager)
    {
        $this->pager = $pager;
    }

    /**
     * Returns the fully qualified contact class name
     *
     * @return string
     **/
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Finds a contact by given id
     *
     * @param  string $id
     *
     * @return ContactInterface
     */
    public function findContactById($id)
    {
        return $this->findContactBy(array('id' => $id));
    }

    /**
     * Creates an empty Contact instance
     *
     * @param integer $id
     *
     * @return ContactInterface
     */
    public function createContact($id = null)
    {
        $class = $this->getClass();
        $contact = new $class;

        if (null !== $id) {
            $contact->setId($id);
        }

        $event = new ContactEvent($contact);
        $this->dispatcher->dispatch(Events::CONTACT_CREATE, $event);

        return $contact;
    }

    /**
     * Persists a contact.
     *
     * @param ContactInterface $contact
     *
     * @return boolean
     */
    public function save(ContactInterface $contact)
    {
        $event = new ContactEvent($contact);
        $this->dispatcher->dispatch(Events::CONTACT_PRE_PERSIST, $event);

        if ($event->isPropagationStopped()) {
            return false;
        }

        $this->doSave($contact);
        $this->dispatcher->dispatch(Events::CONTACT_POST_PERSIST, $event);

        return true;
    }

    /**
     * Removes a contact.
     *
     * @param ContactInterface $contact
     *
     * @return boolean
     */
    public function remove(ContactInterface $contact)
    {
        $event = new ContactEvent($contact);
        $this->dispatcher->dispatch(Events::CONTACT_PRE_REMOVE, $event);

        if ($event->isPropagationStopped()) {
            return false;
        }

        $this->doRemove($contact);

        $this->dispatcher->dispatch(Events::CONTACT_POST_REMOVE, $event);

        return true;
    }

    /**
     * Deletes a list of contacts
     *
     * @param array $ids
     *
     * @return boolean
     */
    public function delete(array $ids)
    {
        $event = new ContactDeleteEvent($ids);
        $this->dispatcher->dispatch(Events::CONTACT_PRE_DELETE, $event);

        if ($event->isPropagationStopped()) {
            return false;
        }

        // get all images before deleting contacts
        $images = $this->getImages($ids);

        // get upload directory
        $contactClass = $this->getClass();
        $contact = new $contactClass;
        $uploadDir = $contact->getUploadRootDir();

        // delete contacts
        $this->doDelete($ids);

        $this->dispatcher->dispatch(Events::CONTACT_POST_DELETE, $event);

        // delete images
        foreach ($images as $image) {
            $imageFile = $uploadDir . '/' . $image['imagePath'];

            if (file_exists($imageFile) and is_file($imageFile)) {
                @unlink($imageFile);
            }
        }

        return true;
    }

    /**
     * Returns the pagination html
     *
     * @return string
     */
    public function getPaginationHtml()
    {
        return (null === $this->pager) ? '' : $this->pager->getHtml();
    }

    /**
     * {@inheritDoc}
     */
    public function getUserId()
    {
        return $this->context->getToken()->getUser()->getId();
    }

    /**
     * Deletes all contacts belonging to a single user
     *
     * @param integer $userId
     *
     * @return array
     */
    public function deleteUser($userId)
    {
        $event = new UserDeleteEvent($userId);
        $this->dispatcher->dispatch(Events::USER_PRE_DELETE, $event);

        $this->doDeleteUser($userId);

        //remove image folder
        $contactClass = $this->getClass();
        $contact = new $contactClass;
        $uploadDir = $contact->getUploadRootDir() . '/' . $userId;

        $status = true;
        $statusMessage = 'User id - ' . $userId . ' contacts deleted';

        $fs = new Filesystem();

        try{
            $fs->remove($uploadDir);
        }catch(\Exception $e){
            $status = false;
            $statusMessage = 'Error deleting image folder '. $uploadDir .
                ' - ' . $e->getMessage();
        }

        $this->dispatcher->dispatch(Events::USER_POST_DELETE, $event);

        return array('status' => $status, 'message' => $statusMessage);
    }

    /**
     * Performs the removal of all contacts belonging to a user.
     *
     * @param integer   $userId
     *
     * @return array
     */
    abstract protected function doDeleteUser($userId);

    /**
     * Returns an array of images for the given ids.
     *
     * @param array     $ids
     *
     * @return array(
     *      0 => array('imagePath'=>'...'),
     *      1 => array('imagePath'=>'...'),
     *      ....
     *  )
     */
    abstract protected function getImages($ids);

    /**
     * Performs the persistence of the contact.
     *
     * @param ContactInterface $contact
     */
    abstract protected function doSave(ContactInterface $contact);

    /**
     * Performs the removal of the contact.
     *
     * @param ContactInterface $contact
     */
    abstract protected function doRemove(ContactInterface $contact);

    /**
     * Performs the removal of a list of contacts.
     *
     * @param array $ids
     */
    abstract protected function doDelete($ids);

}
