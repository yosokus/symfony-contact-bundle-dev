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
 * An event that occurs related to deleting all contacts of a user.
 */
class UserDeleteEvent extends Event
{
    private $userId;

    /**
     * Constructor.
     *
     * @param integer $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Returns the user id for this event.
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
