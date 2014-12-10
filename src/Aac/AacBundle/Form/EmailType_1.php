<?php

namespace Aac\AacBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EmailType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // ->add('nombre del campo', 'tipo de campo', array con las opciones)
        $builder
            ->add('de', 'text', array(
                'attr'      =>  array('class' => 'col-md-4'),
                'label'     =>  'De : '
            ))                
            ->add('para', 'text', array(
                'attr'      =>  array('class' => 'col-md-4'),
                'label'     =>  'Para : '
            ))
            ->add('asunto', 'text', array(
                'attr'      =>  array('class' => 'col-md-4'),
                'label'     =>  'Asunto : '
            ))
            ->add('cabecera', 'text', array(
                'attr'      =>  array('class' => 'col-md-4'),
                'label'     =>  'Cabecera : '
            ))
            ->add('texto', 'textarea', array(
                'attr'      =>  array('class' => 'col-md-4'),
                'label'     =>  'Texto : '
            ));

    }    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aac\AacBundle\Entity\Email',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'Email';
    }
}

