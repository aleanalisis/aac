<?php

namespace Aac\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder
            ->add('nombre', 'text', array(
                'attr'      => array('class' => 'col-md-4')
            ))
            ->add('telefono', 'text', array(
                'attr'      => array('class' => 'col-md-2'),
                'required'  => FALSE,
            ))
            ->add('captcha', 'captcha', array(
                'width' => 200,
                'height' => 50,
                'length' => 6,
            ));                
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'aac_user_registration';
    }
}