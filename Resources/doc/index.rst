Installation
============

1. Add the following lines to your ``composer.json``

.. code-block:: php

    // composer.json
    "require": {
    //...,
        "rps/core-bundle": "dev-master",
        "rps/contact-bundle": "dev-master"
    }

2. Update the dependency

.. code-block:: bash

    php composer.phar update rps/contact-bundle

**Or** you can do both steps in one command

.. code-block:: bash

    php composer.phar require rps/core-bundle:dev-master rps/contact-bundle:dev-master


#. Deploy the bundle assets.

Run the following command

.. code-block:: php

    php app/console assets:install web [--symlink]

use the ``--symlink`` option to use symlinks to bundle assets.


#. Enable the bundle

Add the RPSCoreBundle and RPSContactBundle to your application kernel.

.. code-block:: php

    // app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            // ...,
            new RPS\CoreBundle\RPSCoreBundle(),
            new RPS\ContactBundle\RPSContactBundle(),
        );
    }


#. Import the RPSContactBundle routing file

Add the following to you routing file

.. code-block:: yml

    # app/config/routing.yml
    rps_contact:
        resource: "@RPSContactBundle/Resources/config/routing.yml"
        prefix:   /


#. Update your schema

Run the following command

.. code-block:: bash

    app/console doctrine:schema:update --force


#. Enable the translator in your configuration

.. code-block:: yml

    # app/config/config.yml
    framework:
        translator: { fallback: ~ }


For more information about translations, check the `Symfony Translation documentation`_


#. Install a User Manager

You **must** install a User manager (e.g. `FOSUserBundle`_ ,  `SonataUserBundle`_),
before you can use the RPSContactBundle.

The RPSContactBundle is designed to work with any user manager bundle that has a ``user id`` field.

Check the documentation of your preferred user manager for information on
how to install it.


#. Install the LiipImagineBundle_ and configure it properly (see the docs for more information).


Other topics
============

#. `Doctrine Configuration`_

#. `Image Manager Configuration`_

#. `Pager Configuration`_

#. `Views/Templates`_

#. `Default Configuration`_


.. _`Symfony Translation documentation`: http://symfony.com/doc/current/book/translation.html
.. _LiipImagineBundle: https://github.com/liip/LiipImagineBundle
.. _`FOSUserBundle`: https://github.com/FriendsOfSymfony/FOSUserBundle
.. _`SonataUserBundle`: https://github.com/sonata-project/SonataUserBundle

.. _Doctrine Configuration: Resources/doc/doctrine.rst
.. _`Image Manager Configuration`: Resources/doc/image_manager.rst
.. _Pager Configuration: Resources/doc/pager.rst
.. _`Views/Templates`: Resources/doc/views.rst
.. _`Default Configuration`: Resources/doc/default_configuration.rst
