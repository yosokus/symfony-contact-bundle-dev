<?php

/*
 * This file is part of the RPSContactBundle package.
 *
 * (c) Yos Okusanya <yos.okus@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace RPS\ContactBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ContactExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $uploadDir;

    /**
     * @var string
     */
    private $uploadRootDir;

    /**
     * @var string
     */
    private $defaultAvatar;  // the default avatar

    /**
     * @param ContainerInterface    $container
     * @param string                $contactClass
     * @param string                $defaultAvatar
     */
    public function __construct(ContainerInterface $container, $contactClass, $defaultAvatar='')
    {
        $this->container = $container;

        $contact = new $contactClass;
        $this->uploadDir = $contact->getUploadDir();
        $this->uploadRootDir = $contact->getUploadRootDir();

        if (!$defaultAvatar) {
            $defaultAvatar = 'bundles/rpscontact/images/avatar.png';
        }

        $this->defaultAvatar = $defaultAvatar;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
       return 'rps_contact';
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'rps_get_avatar' => new \Twig_Function_Method($this, 'getAvatar'),
            'rps_trans' => new \Twig_Function_Method($this, 'translate'),
        );
    }

    /**
     * Returns the path to the user avatar or the default avatar if it does not exist.
     *
     * @param string    $path            the user avatar relative path
     * @param boolean   $absolutePath    return relative path or the full path
     *
     * @return string path
     */
    public function getAvatar($path, $absolutePath=true)
    {
        if (!$path) {
            if($absolutePath) {
                return $this->container->get('templating.helper.assets')
                    ->getUrl($this->defaultAvatar);
            }

            return $this->defaultAvatar;
        }

        // If the avatar does not exist, return the default avatar
        if (!file_exists($this->uploadRootDir . '/' . $path)) {
            if ($absolutePath) {
                return $this->container->get('templating.helper.assets')
                    ->getUrl($this->defaultAvatar);
            }

            return $this->defaultAvatar;
        }

        $avatar = $this->uploadDir . '/' . $path; // avatar relative path

        if($absolutePath) {
            $avatar =  $this->container->get('templating.helper.assets')->getUrl($avatar);
        }

        return $avatar;
    }

    /**
     * Returns translated text
     *
     * @param string $msg   message key
     *
     * @return string
     */
    public function translate($msg)
    {
        return $this->container->get('translator')->trans($msg, array(), 'RPSContactBundle');
    }
}