Default Configuration
=====================

.. code-block:: yml

    rps_contact:
        db_driver: orm
        contact_per_page: 25                                        # number of contacts to show on a page
        default_avatar: bundles/rpscontact/images/avatar.png        # relative path of the default avatar
        filter_image: true                                          # resize images

        class:
            model: RPS\ContactBundle\Entity\Entry                   # (optional) contact model class
            manager: RPS\ContactBundle\Entity\EntryManager          # (optional) contact manager class
            pager : RPS\CoreBundle\Pager\PagerfantaORM              # (optional) pager class

        view:
            list: MyprojectMyBundle:Contact:index.html.twig
            show: MyprojectMyBundle:Contact:show.html.twig
            new: MyprojectMyBundle:Contact:new.html.twig
            edit: MyprojectMyBundle:Contact:edit.html.twig

        form:
            contact:
                name: rps_contact_contact
                type: rps_contact_contact
                class: RPS\ContactBundle\Form\Type\ContactType      # contact form class

        service:
            pager: ~                                                # (optional) custom pager service

        image:
            avatar:                                                 # avatar image configuration
                quality: 100
                width: 150
                height: 150

            thumb:                                                  # thumb image configuration
                quality: 100
                width: 100
                height: 100


Each configuration option can be overridden in your app/config/config.yml file


Other topics
============

#. `Installation`_

#. `Doctrine Configuration`_

#. `Image Manager Configuration`_

#. `Pager Configuration`_

#. `Views/Templates`_

.. _Installation: Resources/doc/index.rst
.. _Doctrine Configuration: Resources/doc/doctrine.rst
.. _`Image Manager Configuration`: Resources/doc/image_manager.rst
.. _Pager Configuration: Resources/doc/pager.rst
.. _`Views/Templates`: Resources/doc/views.rst
