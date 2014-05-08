Views/Templates
===============

You can specify custom templates/views by overriding the corresponding view parameter e.g.

.. code-block:: yml

    # app/config/config.yml
    rps_contact:
        view:
            list: MyprojectMyBundle:Contact:index.html.twig
            show: MyprojectMyBundle:Contact:show.html.twig
            new: MyprojectMyBundle:Contact:new.html.twig
            edit: MyprojectMyBundle:Contact:edit.html.twig


Other topics
============

#. `Installation`_

#. `Doctrine Configuration`_

#. `Image Manager Configuration`_

#. `Pager Configuration`_

#. `Default Configuration`_

.. _Installation: Resources/doc/index.rst
.. _Doctrine Configuration: Resources/doc/doctrine.rst
.. _Image Manager Configuration: Resources/doc/image_manager.rst
.. _Pager Configuration: Resources/doc/pager.rst
.. _`Default Configuration`: Resources/doc/default_configuration.rst

