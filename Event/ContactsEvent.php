<?php

/*
 * This file is part of the RPSContactBundle package.
 *
 * (c) Yos Okusanya <yos.okus@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RPS\ContactBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Base class for events that occurs related to performing
 * a batch operation on a list of contacts.
 */
class ContactsEvent extends Event
{
    private $ids;

    /**
     * Constructs an event.
     *
     * @param array $ids
     */
    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    /**
     * Returns the contact ids for this event.
     *
     * @return array $ids
     */
    public function getIds()
    {
        return $this->ids;
    }
}
