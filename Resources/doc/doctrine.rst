Doctrine configuration
======================

The RPSContactBundle supports both Doctrine ORM and Doctrine MongoDB ODM.
It is configured for Doctrine ORM by default. To use Doctrine MongoDB,
you must set this in the ``db_driver`` config option.

.. code-block:: yml

    rps_contact:
        db_driver: mongodb


For more information about Doctrine ORM setup and configuration, check the
`Symfony Databases and Doctrine documentation`_

For more information about Doctrine MongoDB setup and configuration, check the
`DoctrineMongoDBBundle documentation`_

.. _`Symfony Databases and Doctrine documentation`: http://symfony.com/doc/current/book/translation.html
.. _`DoctrineMongoDBBundle documentation`: http://symfony.com/doc/current/bundles/DoctrineMongoDBBundle/index.html


Using a custom model class
--------------------------

You can specify a custom model class by overriding the contact model class option e.g.

.. code-block:: yml

    # app/config/config.yml
    rps_contact:
        class:
            model: MyProject\MyBundle\Entity\MyContact

Your custom model class may extend the ``RPS\ContactBundle\Model\Contact`` class. If you are not extending the
``RPS\ContactBundle\Model\Contact`` class, your custom model class must implement the
``RPS\ContactBundle\Model\ContactInterface`` interface.


Using a custom manager class
----------------------------

You can specify a custom contact manager class by overriding the manager class option e.g.

.. code-block:: yml

    rps_contact:
        class:
            manager: MyProject\MyBundle\Entity\MyContactManager

Your custom manager class may extend the ``RPS\ContactBundle\Model\ContactManager`` class. If you are not extending the
``RPS\ContactBundle\Model\ContactManager`` class, your custom manager class must implement the
``RPS\ContactBundle\Model\ContactManagerInterface`` interface.


Other topics
============

#. `Installation`_

#. `Image Manager Configuration`_

#. `Pager Configuration`_

#. `Views/Templates`_

#. `Default Configuration`_

.. _Installation: Resources/doc/index.rst
.. _Image Manager Configuration: Resources/doc/image_manager.rst
.. _`Pager Configuration`: Resources/doc/image_manager.rst
.. _`Views/Templates`: Resources/doc/views.rst
.. _`Default Configuration`: Resources/doc/default_configuration.rst
