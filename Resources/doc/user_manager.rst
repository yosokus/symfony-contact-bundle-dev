Image Manager
=============

The RPSContactBundle uses the ``LiipImagineBundle`` to resize images on the fly.

To use ``LiipImagineBundle`` as the image manager , you must install the LiipImagineBundle_
and configure it properly (see the docs for more information).

.. _LiipImagineBundle: https://github.com/liip/LiipImagineBundle

If the LiipImagineBundle is not installed, the RPSContactBundle will not resize images.


Using a custom Image Manager
----------------------------

To use a different/custom Image manager to resize images, you should
create a custom list and detail view and set the RPSContactBundle view config option to your custom views

.. code-block:: yml

    # app/config/config.yml
    rps_contact:
        view:
            list: MyprojectMyBundle:Contact:index.html.twig
            show: MyprojectMyBundle:Contact:new.html.twig

Your Image manager should implement a twig extension that parses the contact <img> tag src attribute.


Other topics
============

#. `Installation`_

#. `Doctrine Configuration`_

#. `Pager Configuration`_

#. `Views/Templates`_

#. `Default Configuration`_

.. _Installation: Resources/doc/index.rst
.. _`Doctrine Configuration`: Resources/doc/doctrine.rst
.. _`Pager Configuration`: Resources/doc/pager.rst
.. _`Views/Templates`: Resources/doc/views.rst
.. _`Default Configuration`: Resources/doc/default_configuration.rst

