<?php

namespace Aac\AacBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArchivoType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // ->add('nombre del campo', 'tipo de campo', array con las opciones)
        //print_r($options);exit;
        $builder
            ->add('file', 'file', array(
                'attr'      => array('class' => 'col-md-4',
                'label'     => 'Archivo')
            ))
            ->add('descripcion', 'textarea', array(
                'attr'      => array('class' => 'col-md-4')    
            ))
            ->add('para', 'integer', array(
                'attr'      => array('class' => 'col-md-4')
            ));

    }    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aac\AacBundle\Entity\Archivo',
            'csrf_protection' => FALSE,
            'csrf_field_name' => '_token',
        ));
    }

    public function getName()
    {
        return 'Archivo';
    }
}
