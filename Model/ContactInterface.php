<?php

/*
 * This file is part of the RPSContactBundle package.
 *
 * (c) Yos Okusanya <yos.okusanya@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RPS\ContactBundle\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface to be implemented by the contact class.
 */

interface ContactInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set first name
     *
     * @param string $firstName
     */
    public function setFirstName($firstName);

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Set last name
     *
     * @param string $lastName
     */
    public function setLastName($lastName);

    /**
     * Get last name
     *
     * @return string
     */
    public function getLastName();

    /**
     * Set phone
     *
     * @param string $phone
     */
    public function setPhone($phone);

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone();

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email);

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set twitter_username
     *
     * @param string $twitterUsername
     */
    public function setTwitterUsername($twitterUsername);

    /**
     * Get twitter_username
     *
     * @return string
     */
    public function getTwitterUsername();

    /**
     * Set facebook_username
     *
     * @param string $facebookUsername
     */
    public function setFacebookUsername($facebookUsername);

    /**
     * Get facebook_username
     *
     * @return string
     */
    public function getFacebookUsername();

    /**
     * Set facebook_uid
     *
     * @param string $facebookUid
     */
    public function setFacebookUid($facebookUid);

    /**
     * Get facebook_uid
     *
     * @return string
     */
    public function getFacebookUid();

    /**
     * Set gplus_username
     *
     * @param string $gplusUsername
     */
    public function setGplusUsername($gplusUsername);

    /**
     * Get image_path
     *
     * @return string
     */
    public function getGplusUsername();

    /**
     * Set gplus_uid
     *
     * @param string $gplusUid
     */
    public function setGplusUid($gplusUid);

    /**
     * Get image_path
     *
     * @return string
     */
    public function getGplusUid();

    /**
     * Set image_path
     *
     * @param string $imagePath
     */
    public function setImagePath($imagePath);

    /**
     * Get image_path
     *
     * @return string
     */
    public function getImagePath();

    /**
     * Set user_id
     *
     * @param integer $userId
     */
    public function setUserId($userId);

    /**
     * Get user_id
     *
     * @return integer
     */
    public function getUserId();

    /**
     * Get avatar.
     *
     * @return UploadedFile
     */
    public function getAvatar();

    /**
     * Sets avatar.
     *
     * @param UploadedFile $avatar
     */
    public function setAvatar(UploadedFile $avatar = null);

    /**
     * get the uploaded image filename
     * @return string
     */
    function getAvatarFilename();

    /**
     * get the uploaded image file extension
     * @return string
     */
    function getAvatarFileExt();

    /**
     * Get the relative path to the upload directory
     *
     * @return string
     */
    public function getUploadDir();

    /**
     * Get the absolute path to the upload directory
     *
     * @return string
     */
    public function getUploadRootDir();

    /**
     * Get the absolute path to the contact image
     *
     * @return null|string
     */
    public function getAvatarAbsolutePath();

    /**
     * Post persist/update()
     */
    public function postSave();

    /**
     * Stores the avatar file location for removal
     * after deleting the contact
     */
    public function preRemove();

    /**
     * Deletes avatar after deleting contact
     */
    public function postRemove();

    /**
     * Returns the upload error messages
     *
     * @return string
     */
    public function getUploadErrorMessage();

    /**
     * set error message
     *
     * @param string $msg
     */
    public function setUploadErrorMessage($msg);

    /**
     * Returns the upload error flag
     *
     * @return boolean
     */
    public function hasUploadError();

}
