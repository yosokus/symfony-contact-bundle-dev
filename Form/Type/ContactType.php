<?php

/*
 * This file is part of the RPSContactBundle
 *
 * (c) Yos Okusanya <yos.okus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RPS\ContactBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    private $contactClass;

    /**
     * Constructor
     *
     * @param string $contactClass
     */
    public function __construct($contactClass)
    {
        $this->contactClass = $contactClass;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', 'text', array(
                'label' => 'form.first_name',
                'translation_domain' => 'RPSContactBundle'
            ))
            ->add('lastName', 'text', array(
                'label' => 'form.last_name',
                'required'=>false,
                'translation_domain' => 'RPSContactBundle',
            ))
            ->add('phone', 'text', array(
                'label' => 'form.phone',
                'required'=>false,
                'translation_domain' => 'RPSContactBundle',
            ))
            ->add('email', 'email', array(
                'label' => 'form.email',
                'required'=>false,
                'translation_domain' => 'RPSContactBundle',
            ))
            ->add('facebookUsername', 'text', array(
                'label' => 'form.facebook_username',
                'required'=>false,
                'translation_domain' => 'RPSContactBundle',
            ))
            ->add('facebookUid', 'integer', array(
                'label' => 'form.facebook_uid',
                'required'=>false,
                'translation_domain' => 'RPSContactBundle',
            ))
            ->add('twitterUsername', 'text', array(
                'label' => 'form.twitter_username',
                'required'=>false,
                'translation_domain' => 'RPSContactBundle',
            ))
            ->add('gplusUsername', 'text', array(
                'label' => 'form.gplus_username',
                'required'=>false,
                'translation_domain' => 'RPSContactBundle',
            ))
            ->add('gplusUid', 'integer', array(
                'label' => 'form.gplus_uid',
                'required'=>false,
                'translation_domain' => 'RPSContactBundle',
            ))
            ->add('avatar', 'file', array(
                'label' => 'form.avatar',
                'required'=>false,
                'mapped' => false,
                'translation_domain' => 'RPSContactBundle',
            ))
            ->add('save', 'submit', array(
                'label' => 'form.save',
                'translation_domain' => 'RPSContactBundle',
            ))
            ->add('saveAndNew', 'submit', array(
                'label' => 'form.save_and_new',
                'translation_domain' => 'RPSContactBundle',
            ))
            ->getForm();
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => $this->contactClass,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rps_contact_contact';
    }
}