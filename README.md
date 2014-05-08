RPS Contact Bundle (Beta version)
===================================

A simple yet flexible user contact package based on Doctrine2 (ORM and MongoDB) and Symfony2.

#Documentation

The bulk of the documentation is stored in the `Resources/doc/index.rst`
file in this bundle.

[Read the Documentation](Resources/doc/index.rst)

#Installation

All the installation instructions are located in the [documentation](Resources/doc/index.rst).

## 1. Add the following lines to your ``composer.json``

    // composer.json
    "require": {
    //...,
        "rps/core-bundle": "dev-master",
        "rps/contact-bundle": "dev-master"
    }

## 2. Update the dependency

    php composer.phar update rps/contact-bundle

**Or** you can do both steps in one command

    php composer.phar require rps/core-bundle:dev-master rps/contact-bundle:dev-master

## 3. Deploy the bundle assets.

Run the following command

    php app/console assets:install web [--symlink]

use the ``--symlink`` option to use symlinks to bundle assets.


## 4. Enable the bundle

Add the RPSCoreBundle and RPSContactBundle to your application kernel.

    // app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            // ...,
            new RPS\CoreBundle\RPSCoreBundle(),
            new RPS\ContactBundle\RPSContactBundle(),
        );
    }

## 5. Import the RPSContactBundle routing file

Add the following to you routing file

    # app/config/routing.yml
    rps_contact:
        resource: "@RPSContactBundle/Resources/config/routing.yml"
        prefix:   /

## 6. Update your schema

Run the following command

    app/console doctrine:schema:update --force

## 7. Enable the translator in your configuration

    # app/config/config.yml
    framework:
        translator: { fallback: ~ }

For more information about translations, check the
[Symfony Translation documentation](http://symfony.com/doc/current/book/translation.html)


## 8. Install a User Manager

You **must** install a User manager (e.g.
[FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle) ,
[SonataUserBundle](https://github.com/sonata-project/SonataUserBundle)),
before you can use the RPSContactBundle.

The RPSContactBundle is designed to work with any user manager bundle that has a ``user id`` field.

Check the documentation of your preferred user manager for information on
how to install it.


## 9. Install the [LiipImagineBundle](https://github.com/liip/LiipImagineBundle)
and configure it properly (see the docs for more information).


## Other topics
1. [Doctrine Configuration](Resources/doc/doctrine.rst)
2. [Image Manager Configuration](Resources/doc/doctrine.rst)
3. [Pager Configuration](Resources/doc/pager.rst)
4. [Views/Templates](Resources/doc/views.rst)
5. [Default Configuration](Resources/doc/default_configuration.rst)


#License

This bundle is available under the [MIT license](Resources/meta/LICENSE).
