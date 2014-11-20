<?php

namespace Aac\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModificarFormType extends AbstractType
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
            ->add('roles', 'choice', array(
                'choices' => array( 'ROLE_INVITADO'     =>  'ROLE_INVITADO',
                                    'ROLE_REGISTRADO'   =>  'ROLE_REGISTRADO',
                                    'ROLE_INTERVENTOR'  =>  'ROLE_INTERVENTOR',
                                    'ROLE_ADMIN'        =>  'ROLE_ADMIN',
                                    ),
                'label' => 'Roles',
                'expanded' => false,
                'multiple' => true,
                'mapped' => true,
            ))
            ->add('plainPassword', 'repeated', array(
                'type' => 'hidden',
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ));        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aac\UserBundle\Entity\User',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
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