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
                'attr'          =>  array('class' => 'col-md-3',
                'disabled'      =>  true
                ),
                'label_attr'    =>  array('class' => 'col-md-2 control-label text-right'),
                'label'         =>  'De : '
            ))                
            ->add('para', 'text', array(
                'attr'          =>  array('class' => 'col-md-3',
                'disabled'      =>  true
                ),
                'label_attr'    =>  array('class' => 'col-md-2 control-label text-right'),
                'label'         =>  'Para : '
            ))
            ->add('asunto', 'text', array(
                'attr'          =>  array('class' => 'col-md-4',
                'autofocus'     =>  'autofocus'    
                ),
                'label_attr'    =>  array('class' => 'col-md-2 control-label text-right'),
                'label'         =>  'Asunto : '
            ))
            ->add('cabecera', 'textarea', array(
                'attr'          =>  array('class' => 'col-md-5'),
                'label_attr'    =>  array('class' => 'col-md-2 control-label text-right'),
                'label'         =>  'Cabecera : '                
            ))
            ->add('texto', 'textarea', array(
                'attr'          =>  array('class' => 'col-md-5',
                'rows'          =>  8,    
                ),
                'label_attr'    =>  array('class' => 'col-md-2 control-label text-right'),
                'label'         =>  'Texto del Email : '                
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

