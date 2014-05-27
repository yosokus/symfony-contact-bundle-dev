<?php

/*
 * This file is part of the RPSContactBundle package.
 *
 * (c) Yos Okusanya <yos.okusanya@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RPS\ContactBundle\Event;

/**
 * Contact Events.
 */
final class Events
{
    /**
     * The CREATE event occurs when the manager creates
     * a new instance of a Contact.
     *
     * The listener receives a RPS\ContactBundle\Event\ContactEvent instance.
     *
     * @var string
     */
    const CONTACT_CREATE = 'rps_contact.contact.create';

    /**
     * The PRE_PERSIST occurs prior to the manager persisting the contact.
     *
     * The listener receives a RPS\ContactBundle\Event\ContactEvent instance.
     *
     * Persistence can be aborted by calling $event->isPropagationStopped()
     *
     * @var string
     */
    const CONTACT_PRE_PERSIST = 'rps_contact.contact.prePersist';

    /**
     * The POST_PERSIST event occurs after the Contact is persisted.
     *
     * The listener receives a RPS\ContactBundle\Event\ContactEvent instance.
     *
     * @var string
     */
    const CONTACT_POST_PERSIST = 'rps_contact.contact.postPersist';

    /**
     * The PRE_REMOVE event occurs prior to the manager removing the contact.
     *
     * The listener receives a RPS\ContactBundle\Event\ContactEvent instance.
     *
     * Contact removal can be aborted by calling $event->isPropagationStopped()
     *
     * @var string
     */
    const CONTACT_PRE_REMOVE = 'rps_contact.contact.preDelete';

    /**
     * The POST_REMOVE event occurs after removing the Contact.
     *
     * The listener receives a RPS\ContactBundle\Event\ContactEvent instance.
     *
     * @var string
     */
    const CONTACT_POST_REMOVE = 'rps_contact.contact.postDelete';

    /**
     * The PRE_DELETE event occurs prior to the manager
     * deleting a list of contacts.
     *
     * The listener receives a RPS\ContactBundle\Event\ContactDeleteEvent instance.
     *
     * Deleting the contacts can be aborted by calling $event->isPropagationStopped()
     *
     * @var string
     */

    const CONTACT_PRE_DELETE = 'rps_contact.contact.preDelete';

    /**
     * The POST_DELETE event occurs after deleting a list of contacts
     *
     * The listener receives a RPS\ContactBundle\Event\ContactDeleteEvent instance.
     *
     * @var string
     */
    const CONTACT_POST_DELETE = 'rps_contact.contact.postDelete';

    /**
     * The USER_PRE_DELETE event occurs prior to the manager
     * deleting all contacts belonging to a user.
     *
     * The listener receives a RPS\ContactBundle\Event\UserDeleteEvent instance.
     *
     * @var string
     */

    const USER_PRE_DELETE = 'rps_contact.user.preDelete';

    /**
     * The USER_POST_DELETE event occurs after deleting
     * all contacts belonging to a user.
     *
     * The listener receives a RPS\ContactBundle\Event\UserDeleteEvent instance.
     *
     * @var string
     */
    const USER_POST_DELETE = 'rps_contact.user.postDelete';
}
