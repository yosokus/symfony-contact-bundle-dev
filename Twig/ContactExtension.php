<?php

/*
 * This file is part of the RPSContactBundle package.
 *
 * (c) Yos Okusanya <yos.okusanya@gmail.com>
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
     * @var string
     */
    private $imagineExtension;  // the ImagineBundle twig extension

    /**
     * @param ContainerInterface    $container
     * @param string                $contactClass
     * @param string                $defaultAvatar
     * @param mixed                 $imagineExtension (Liip\ImagineBundle\Templating\ImagineExtension|null)
     */
    public function __construct(ContainerInterface $container, $contactClass,
                                $defaultAvatar = '', $imagineExtension = null)
    {
        $this->container = $container;
        $this->imagineExtension = $imagineExtension;

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
            'rps_filter_image' => new \Twig_Function_Method($this, 'filterImage'),
            'rps_trans' => new \Twig_Function_Method($this, 'translate'),
        );
    }

    /**
     * Returns the path to the user avatar or the default avatar if it does not exist.
     *
     * @param string    $path            the user avatar relative path
     * @param boolean   $absolutePath    return relative path or the full path
     * @param string    $filterName      the LiipImagineBunble filter name
     *
     * @return string path
     */
    public function getAvatar($path, $absolutePath=true, $filterName='')
    {
        $avatar = $this->defaultAvatar; // default avatar relative path

        if ($path) {
            // return the contact avatar if it exists
            $avatarAbsolutePath = $this->uploadRootDir . '/' . $path;
            if (is_file($avatarAbsolutePath) and file_exists($avatarAbsolutePath)) {
                $avatar = $this->uploadDir . '/' . $path; // avatar relative path
            }
        }

        if($absolutePath) {
            $avatar =  $this->container->get('templating.helper.assets')
                ->getUrl($avatar);  // avatar absolute path
        }

        if ($filterName) {
            $avatar = $this->filterImage($avatar, $filterName, false); // filtered image path
        }

        return $avatar;
    }

    /**
     * Returns the filtered image path if the LiipImagineBundle is enabled
     *
     * @param string    $path       message key
     * @param string    $filter     message key
     * @param boolean   $absolute   message key
     *
     * @return string
     */
    public function filterImage($path, $filter, $absolute = false)
    {
        if (null === $this->imagineExtension) {
            return $path;
        }

        return $this->imagineExtension->render($path, $filter, $absolute);
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