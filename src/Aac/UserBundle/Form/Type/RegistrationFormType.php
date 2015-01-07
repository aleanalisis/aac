<?php

namespace Aac\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder
            ->add('empresa', 'text', array(
                'attr'      => array('class' => 'col-md-4')
            ))            
            ->add('nombre', 'text', array(
                'attr'      => array('class' => 'col-md-4')
            ))
            ->add('telefono', 'text', array(
                'attr'      => array('class' => 'col-md-2'),
                'required'  => FALSE,
            ))
            ->add('recaptcha', 'ewz_recaptcha', array(
                'attr'          =>  array(
                'options'       => array(
                                'theme' => 'light',
                                'type'  => 'image'
                    )
                ),
                'label_attr'    =>  array('class'   =>  'col-md-2 control-label text-right'),
                'label'         =>  'Captcha : ',    
                'mapped'      => false,
                'constraints' => array(
                new True()
                )                    
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