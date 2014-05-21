Pager Configuration
===================

The RPSContactBundle uses the ``WhiteOctoberPagerfantaBundle`` for pagination.
Pagination is enabled by default.

To use ``WhiteOctoberPagerfantaBundle`` as the RPSContactBundle pager ,
you must install the WhiteOctoberPagerfantaBundle_.

If the WhiteOctoberPagerfantaBundle is not installed, the RPSContactBundle will disable pagination.

To limit the number of contacts shown, set the ``contact_per_page`` config option

.. code-block:: yml

    # app/config/config.yml
    rps_contact:
        contact_per_page: 25


Using a custom pager manager class
----------------------------------

You can specify your custom pager manager class by overriding the pager class option e.g.

.. code-block:: yml

    # app/config/config.yml
    rps_contact:
        class:
            pager: MyProject\MyBundle\Pager\Pager

Your custom class **must** implement the ``\RPS\CoreBundle\Pager\PagerInterface`` interface.


Using a custom pager service
----------------------------

You can specify a custom pager service to handle the contacts pagination
by setting the pager service config option.

.. code-block:: yml

    # app/config/config.yml
    rps_contact:
        service:
            pager: my_pager

Your pager service class **must** implement the ``\RPS\CoreBundle\Pager\PagerInterface`` interface.


Other topics
============

#. `Installation`_

#. `Doctrine Configuration`_

#. `Image Manager Configuration`_

#. `Views/Templates`_

#. `Default Configuration`_

.. _WhiteOctoberPagerfantaBundle:: https://github.com/whiteoctober/WhiteOctoberPagerfantaBundle‎

.. _Installation: Resources/doc/index.rst
.. _`Doctrine Configuration`: Resources/doc/doctrine.rst
.. _Image Manager Configuration: Resources/doc/image_manager.rst
.. _`Views/Templates`: Resources/doc/views.rst
.. _`Default Configuration`: Resources/doc/default_configuration.rst
