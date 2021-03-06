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

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Base class for the contact class.
 */
abstract class Contact implements ContactInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $facebookUsername;

    /**
     * @var string
     */
    protected $facebookUid;

    /**
     * @var string
     */
    protected $twitterUsername;

    /**
     * @var string
     */
    protected $gplusUsername;

    /**
     * @var string
     */
    protected $gplusUid;

    /**
     * @var string
     */
    protected $imagePath;

    /**
     * @var integer
     */
    protected $userId;

    /**
     * @var UploadedFile
     */
    private $avatar;

    /**
     * @var string
     */
    private $avatarFilename;        // temp avatar filename

    /**
     * @var string
     */
    private $avatarFileExt;         // temp avatar file extension

    /**
     * @var string
     */
    private $tempAvatarPath;        // temp avatar location

    /**
     * @var boolean
     */
    private $hasUploadError = false; // upload error flag

    /**
     * @var string
     */
    private $uploadErrorMsg = null; // upload error message

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set first name
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set last name
     *
     * @param string $lastName
     */
    public function setLastname($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Get last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set twitterUsername
     *
     * @param string $twitterUsername
     */
    public function setTwitterUsername($twitterUsername)
    {
        $this->twitterUsername = $twitterUsername;
    }

    /**
     * Get twitterUsername
     *
     * @return string
     */
    public function getTwitterUsername()
    {
        return $this->twitterUsername;
    }

    /**
     * Set facebookUsername
     *
     * @param string $facebookUsername
     */
    public function setFacebookUsername($facebookUsername)
    {
        $this->facebookUsername = $facebookUsername;
    }

    /**
     * Get facebookUsername
     *
     * @return string
     */
    public function getFacebookUsername()
    {
        return $this->facebookUsername;
    }

    /**
     * Set facebookUid
     *
     * @param string $facebookUid
     */
    public function setFacebookUid($facebookUid)
    {
        $this->facebookUid = $facebookUid;
    }

    /**
     * Get facebookUid
     *
     * @return string
     */
    public function getFacebookUid()
    {
        return $this->facebookUid;
    }

    /**
     * Set gplusUsername
     *
     * @param string $gplusUsername
     */
    public function setGplusUsername($gplusUsername)
    {
        $this->gplusUsername = $gplusUsername;
    }

    /**
     * Get imagePath
     *
     * @return string
     */
    public function getGplusUsername()
    {
        return $this->gplusUsername;
    }

    /**
     * Set gplusUid
     *
     * @param string $gplusUid
     */
    public function setGplusUid($gplusUid)
    {
        $this->gplusUid = $gplusUid;
    }

    /**
     * Get imagePath
     *
     * @return string
     */
    public function getGplusUid()
    {
        return $this->gplusUid;
    }

    /**
     * Set imagePath
     *
     * @param string $imagePath
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
    }

    /**
     * Get imagePath
     *
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get the relative path to the upload directory
     *
     * @return string
     */
    public function getUploadDir()
    {
        return 'uploads/images/rpscontact';
    }

    /**
     * Get the absolute path to the upload directory
     *
     * @return string
     */
    public function getUploadRootDir()
    {
        return __DIR__ . '/../../../../../../web/' . $this->getUploadDir();
    }

    /**
     * Get the absolute path to the contact image
     *
     * @return null|string
     */
    public function getAvatarAbsolutePath()
    {
        return null === $this->imagePath ? null : $this->getUploadRootDir() . '/' .$this->imagePath;
    }

    /**
     * Get avatar.
     *
     * @return UploadedFile
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Sets avatar.
     *
     * @param UploadedFile $avatar
     */
    public function setAvatar(UploadedFile $avatar = null)
    {
        if (null === $avatar) {
            return;
        }

        $ext = $avatar->guessExtension();

        //validate image
        if ( in_array(strtolower($ext), array('png','jpg', 'jpeg', 'gif')) ) {
            $this->avatar = $avatar;
            $this->storeAvatarToRemove();

            $filename = sha1(uniqid(mt_rand(), true));
            $this->avatarFilename = $filename;
            $this->avatarFileExt = $ext;
            $this->imagePath = $this->userId . '/' . $filename . '.' . $ext;
        } else {
            $this->avatar = null;
            $this->setUploadErrorMessage('flash.error.format_not_supported');
        }
    }

    /**
     * Store avatar filename to delete after save.
     */
    public function storeAvatarToRemove()
    {
        // exit if avatar already stored
        if (isset($this->tempAvatarPath)) {
            return;
        }

        // check if we have an old avatar
        if (isset($this->imagePath)) {
            $this->tempAvatarPath = $this->getAvatarAbsolutePath();
            $this->imagePath = null;
        } else {
            $this->imagePath = null;
        }
    }

    /**
     * Returns the uploaded image filename
     *
     * @return string
     */
    function getAvatarFilename()
    {
        return $this->avatarFilename;
    }

    /**
     * Returns the uploaded image file extension
     *
     * @return string
     */
    function getAvatarFileExt()
    {
        return $this->avatarFileExt;
    }

    /**
     * Post persist/update.
     * Move avatar and delete old avatar (if exists)
     */
    public function postSave()
    {
        // delete old avatar
        if (isset($this->tempAvatarPath))  {
            if( is_file($this->tempAvatarPath) and file_exists($this->tempAvatarPath) ) {
                @unlink($this->tempAvatarPath);
            }

            $this->tempAvatarPath = null;   // clear the temp avatar path
        }

        // upload avatar
        if (null === $this->avatar) {
            return;
        }

        $uploadDir = $this->getUploadRootDir() . '/' . $this->userId ;
        $filename = $this->avatarFilename . '.' . $this->avatarFileExt;

        // throws a FileException exception
        // catch exception (if thrown) and report error in the frontend
        try{
            $this->avatar->move($uploadDir, $filename);
        }catch(FileException $e){
            $this->setUploadErrorMessage('flash.error.file_upload');
            #$this->setUploadErrorMessage($e->getMessage());
        }

        $this->avatar = null;   // clear avatar field
    }

    /**
     * Stores the avatar file location for removal
     * after deleting the contact
     */
    public function preRemove()
    {
        $this->tempAvatarPath = $this->getAvatarAbsolutePath();
    }

    /**
     * Deletes avatar after deleting contact
     */
    public function postRemove()
    {
        if (isset($this->tempAvatarPath)) {
            if( is_file($this->tempAvatarPath) and file_exists($this->tempAvatarPath) ) {
                @unlink($this->tempAvatarPath);
            }
        }
    }

    /**
     * Returns the upload error message
     *
     * @return string
     */
    public function getUploadErrorMessage()
    {
        return $this->uploadErrorMsg;
    }

    /**
     * Set the upload error message
     *
     * @param string $msg
     */
    public function setUploadErrorMessage($msg)
    {
        $this->hasUploadError = true;
        $this->uploadErrorMsg = $msg;
    }

    /**
     * Returns upload error flag
     *
     * @return boolean
     */
    public function hasUploadError()
    {
        return $this->hasUploadError;
    }
}
