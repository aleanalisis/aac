<?php

namespace Aac\AacBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LoginType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // ->add('nombre del campo', 'tipo de campo', array con las opciones)
        $builder
            ->add('usuario', 'text', array(
                'attr'      => array('class' => 'col-md-4')    
            ))
            ->add('password', 'password', array(
                'attr'      => array('class' => 'col-md-4')
            ));
    }    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            
        ));
    }

    public function getName()
    {
        return 'Login';
    }
}
