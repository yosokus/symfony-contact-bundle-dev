Image Manager
=============

The RPSContactBundle uses the ``LiipImagineBundle`` to resize images on the fly.

If the ``LiipImagineBundle`` is not installed, the RPSContactBundle will not resize images.

To use ``LiipImagineBundle`` as the image manager , you must install the LiipImagineBundle_
and configure it properly (see the docs for more information).

.. _LiipImagineBundle: https://github.com/liip/LiipImagineBundle

You can set the dimension and quality of the images by overriding the image configuration options e.g.

.. code-block:: yml

    # app/config/config.yml
    rps_contact:
        image:
            avatar:
                quality: 100
                width: 150
                height: 150

            thumb:
                quality: 100
                width: 100
                height: 100


The RPSContactBundle generates LiipImagineBundle filters for the avatar and thumb images
using the RPSContactBundle image configuration e.g.

.. code-block:: yml

    # app/config/config.yml
    liip_imagine:
        filter_sets:
            rps_contact_avatar:
                quality: 100
                filters:
                    thumbnail: { size: [150, 150], mode: outbound }

            rps_contact_thumb:
                quality: 100
                filters:
                    thumbnail: { size: [100, 100], mode: outbound }


You can override the RPSContactBundle filters and add additional LiipImagineBundle options
by setting them in the ``app/config/config.yml`` file.

See the `LiipImagineBundle Configuration documentation`_ for other available configuration options.


Using a custom Image Manager
----------------------------

1. Create a custom image manager or use an existing image manage bundle (e.g. ``ElendevImageBundle``).

#. Your image manager **must** use a custom twig extension and a custom twig filter to resize images
on the fly.

#. Create a custom list and detail views/templates that uses your custom twig filter
to resize the images. The controller will pass the following image configuration values to your
 view/templates

    ``image_quality``, ``image_width``, ``image_height``, ``filter_image``


#. Set the RPSContactBundle ``list`` and  ``show`` view config option to your custom views
and enable the ``fiilter_image`` config option (optional).

.. code-block:: yml

    rps_contact:
        filter_image: true

        view:
            list: MyprojectMyBundle:Contact:index.html.twig
            show: MyprojectMyBundle:Contact:show.html.twig


Other topics
============

#. `Installation`_

#. `Doctrine Configuration`_

#. `Pager Configuration`_

#. `Views/Templates`_

#. `Default Configuration`_

.. _`LiipImagineBundle Configuration documentation`: https://github.com/liip/LiipImagineBundle/Resources/doc/configuration.md

.. _Installation: Resources/doc/index.rst
.. _`Doctrine Configuration`: Resources/doc/doctrine.rst
.. _`Pager Configuration`: Resources/doc/pager.rst
.. _`Views/Templates`: Resources/doc/views.rst
.. _`Default Configuration`: Resources/doc/default_configuration.rst

