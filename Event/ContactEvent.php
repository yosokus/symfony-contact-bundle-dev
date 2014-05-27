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

use RPS\ContactBundle\Model\ContactInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Base class for a contact related event.
 */
class ContactEvent extends Event
{
    /**
     * @var ContactInterface
     */
    private $contact;

    /**
     * Constructor.
     *
     * @param ContactInterface $contact
     */
    public function __construct(ContactInterface $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Returns the contact for this event.
     *
     * @return ContactInterface
     */
    public function getContact()
    {
        return $this->contact;
    }
}
