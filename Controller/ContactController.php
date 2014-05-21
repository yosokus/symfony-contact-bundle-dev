<?php

/*
 * This file is part of the RPSContactBundle
 *
 * (c) Yos Okusanya <yos.okus@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RPS\ContactBundle\Controller;

use RPS\ContactBundle\Model\ContactInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContactController extends Controller
{
    /**
     * Shows contacts.
     *
     * @param integer $page query offset
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page=1)
    {
        /* throws AccessDeniedException */
        $this->checkIsLoggedIn();
        $userId = $this->getUser()->getId();

        $manager = $this->getManager();
        $limit = $this->container->getParameter('rps_contact.contact_per_page');

        $contacts = $manager->getPaginatedList($page, $limit, array('userId'=>$userId));
        $pagerHtml = $manager->getPaginationHtml();

        $view = $this->getView('list');
        $form = $this->container->get('rps_contact.form_factory.contact');

        //save current page to reuse later
        $this->get('request')->getSession()->set('rps_contact_page', $page);

        return $this->render($view, array(
                'contacts'=>$contacts,
                'form' => $form->createView(),
                'pagination_html' => $pagerHtml,
                'filter_image' =>  $this->container->getParameter('rps_contact.filter_image'),
                'image_quality' =>  $this->container->getParameter('rps_contact.thumb_quality'),
                'image_width' =>  $this->container->getParameter('rps_contact.thumb_width'),
                'image_height' =>  $this->container->getParameter('rps_contact.thumb_height'),
            )
        );

    }

    /**
     * Adds a new Contact/Show contact form.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $this->checkIsLoggedIn();

        $manager = $this->getManager();;
        $form = $this->container->get('rps_contact.form_factory.contact');

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $contact = $form->getData();
                $contact->setAvatar($form['avatar']->getData());    // set contact avatar

                // save contact
                if ($manager->save($contact) !== false) {
                    return $this->onSaveSuccess(
                        $contact,
                        $form->get('saveAndNew')->isClicked(),
                        true
                    );
                } else {
                    $this->setFlashMessage('flash.error.bad_request', array(), 'error');
                }
            }
        }

        return $this->render(
            $this->getView('new'),
            array('form' => $form->createView())
        );
    }

    /**
     * Edits a contact/Show admin form
     *
     * @param Request   $request    Current request
     * @param mixed     $id         Contact id (string|integer)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction(Request $request, $id)
    {
        $this->checkIsLoggedIn();

        $manager = $this->getManager();
        $contact = $manager->findContactById($id);
        if (null === $contact) {
            throw new NotFoundHttpException(sprintf("Contact with id '%s' does not exists.", $id));
        }

        $this->checkUserCan($contact->getUserId());

        $form = $this->container->get('rps_contact.form_factory.contact');
        $form->setData($contact);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $contact = $form->getData();
                $contact->setAvatar($form['avatar']->getData());    //set contact avatar

                if ($request->get('deleteAvatar')) {
                    $contact->storeAvatarToRemove();
                }
            
                if ($manager->save($contact) !== false) {
                    return $this->onSaveSuccess(
                        $contact,
                        $form->get('saveAndNew')->isClicked(),
                        false
                    );
                } else {
                    $this->setFlashMessage('error.bad_request', array(), 'error');
                }
            }
        }
        
        $avatar = $contact->getAvatarAbsolutePath();
        $hasAvatar = (is_file($avatar) and file_exists($avatar)) ? true : false;

        return $this->render(
            $this->getView('edit'),
            array(
                'id' => $id,
                'form' => $form->createView(),
                'contact' => $contact,
                'hasAvatar' => $hasAvatar,
                'filter_image' =>  $this->container->getParameter('rps_contact.filter_image'),
                'image_quality' =>  $this->container->getParameter('rps_contact.avatar_quality'),
                'image_width' =>  $this->container->getParameter('rps_contact.avatar_width'),
                'image_height' =>  $this->container->getParameter('rps_contact.avatar_height'),
            )
        );
    }

    /**
     * Handles successful form submission.
     *
     * @param ContactInterface  $contact
     * @param boolean           $saveAndNew     true if save and new button clicked
     * @param boolean           $isNew          true if saving a new contact
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function onSaveSuccess(ContactInterface $contact, $saveAndNew, $isNew)
    {
        $type = $isNew ? 'save' : 'update';
        $this->setFlashMessage('flash.' . $type. '.success');

        // raise warning if there was an upload error
        if ($contact->hasUploadError()) {
            $this->setFlashMessage(
                $contact->getUploadErrorMessage(),
                array(),
                'warning'
            );
        }

        if ($saveAndNew) {
            return $this->redirect($this->generateUrl('rps_contact_new'));
        }

        return $this->redirect($this->generateUrl('rps_contact_list'));
    }

    /**
     * Show contact
     *
     * @param mixed  $id Contact id (integer|string)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $this->checkIsLoggedIn();
        $contact = $this->getManager()->findContactById($id);
        $this->checkUserCan($contact->getUserId());

        return $this->render(
            $this->getView('show'),
            array(
                'contact' => $contact,
                'filter_image' =>  $this->container->getParameter('rps_contact.filter_image'),
                'image_quality' =>  $this->container->getParameter('rps_contact.avatar_quality'),
                'image_width' =>  $this->container->getParameter('rps_contact.avatar_width'),
                'image_height' =>  $this->container->getParameter('rps_contact.avatar_height'),
            )
        );
    }

    /**
     * Delete contact
     *
     * @param mixed $id Contact id (integer|string)
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction($id)
    {
        $this->checkIsLoggedIn();
        $manager = $this->getManager();
        $contact = $manager->findContactById($id);

        $this->checkUserCan($contact->getUserId()); // validate user

        if($manager->remove($contact) !== false) {
            $this->setFlashMessage('flash.remove.success');
        } else {
            $this->setFlashMessage('flash.remove.error', array('%id%'=>$id), 'error');
        }

        return $this->redirectToListAction();
    }


    /**
     * Delete a list of contacts
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $ids = $request->get('ids', array());

        if (empty($ids)) {
            $this->setFlashMessage('flash.select_error.delete');

            return $this->redirectToListAction();
        }

        $manager = $this->getManager();

        if ($manager->delete($ids) !== false) {
            $nbContacts = count($ids);
            $translated = $this->get('translator')->transChoice(
                'flash.delete.success',
                $nbContacts,
                array('%count%' => $nbContacts),
                'RPSContactBundle'
            );

            $this->get('session')->getFlashBag()->add('notice', $translated);
        } else {
            $this->setFlashMessage('flash.delete.error', array('%ids%'=>implode(', ', $ids)), 'error');
        }

        return $this->redirectToListAction();
    }

    /**
     * Returns to the last view contacts page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    function cancelAction()
    {
        return $this->redirectToListAction();
    }

    /**
     * Redirect to last viewed list page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    function redirectToListAction()
    {
        return $this->redirect(
            $this->generateUrl(
                'rps_contact_list',
                array('page' => $this->get('request')->getSession()->get('rps_contact_page', 1))
            ));
    }

    /**
     * check if current user is logged in
     *
     * @return boolean
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function checkIsLoggedIn()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException('You must login to have access to this section.');
        }

        return true;
    }

    /**
     * check if contact belongs to the user
     *
     * @param mixed     $ownerId  contact user id (integer|string)
     * @param mixed     $userId   current user id (integer|string||null)
     *
     * @return boolean
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function checkUserCan($ownerId, $userId=null)
    {
        //get current user id if userid not provided
        if ($userId === null) {
            $userId = $this->getUser()->getId();
        }

        if ( $userId !== $ownerId ) {
            throw new AccessDeniedException('You do not have access to this contact.');
        }

        return true;
    }

    /**
     * Returns the Contact Manager
     *
     * @return ContactManagerInterface
     */
    public function getManager()
    {
        return $this->container->get('rps_contact.contact_manager');
    }

    /**
     * Returns the requested view
     *
     * @param string    $name
     *
     * @return string
     */
    public function getView($name)
    {
        return $this->container->getParameter('rps_contact.view.' . $name);
    }

    /**
     * Translate and set Flash bag message
     *
     * @param string    $msg
     * @param array     $args
     * @param string    $type
     */
    public function setFlashMessage($msg, $args=array(), $type='notice')
    {
        $msg = $this->get('translator')->trans($msg, $args, 'RPSContactBundle');
        $this->get('session')->getFlashBag()->add($type, $msg);
    }

    /**
     * Set Flash bag message
     *
     * @param string $msg
     * @param string $type
     */
    public function setFlashBag($msg, $type='notice')
    {
        $this->get('session')->getFlashBag()->add($type, $msg);
    }
}
