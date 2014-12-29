<?php

namespace Aac\AacBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EmailType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // ->add('nombre del campo', 'tipo de campo', array con las opciones)
        $disabled = false;
        if (array_key_exists('empty_data', $options)) {
            if($options['empty_data'] == 'ver'){
                $disabled = true;
            }
        }
        
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
                'disabled'      =>  $disabled,
                'autofocus'     =>  'autofocus'    
                ),
                'label_attr'    =>  array('class' => 'col-md-2 control-label text-right'),
                'label'         =>  'Asunto : '
            ))
            ->add('cabecera', 'textarea', array(
                'attr'          =>  array('class' => 'col-md-5'),
                'disabled'      =>  $disabled,
                'label_attr'    =>  array('class' => 'col-md-2 control-label text-right'),
                'label'         =>  'Cabecera : '                
            ))
            ->add('texto', 'textarea', array(
                'attr'          =>  array('class' => 'col-md-5',
                'disabled'      =>  $disabled,
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

