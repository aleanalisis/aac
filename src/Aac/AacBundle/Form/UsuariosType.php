<?php

namespace Aac\AacBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuariosType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // ->add('nombre del campo', 'tipo de campo', array con las opciones)
        $builder
            ->add('nombre', 'text', array(
                'attr'      => array('class' => 'col-md-4')
            ))
            ->add('usuario', 'text', array(
                'attr'      => array('class' => 'col-md-4')    
            ))
            ->add('email', 'email', array(
                'attr'      => array('class' => 'col-md-4')
            ))
            ->add('password', 'repeated', array(
		'attr'      => array('class' => 'col-md-4'),	
                'type' => 'password',
			'invalid_message' => 'Las dos contraseñas deben coincidir',
			'first_options'  => array('label' => 'Contraseña'),
    		'second_options' => array('label' => 'Repetir contraseña'),
			'required' => true,
			'data' => null,
			))
            ->add('telefono', 'text', array(
                'attr'      => array('class' => 'col-md-2'),
                'required'  => FALSE,
            ))
            ->add('estado','choice', array(
                                'attr'      => array('class' => 'col-md-2'),
                                    'choices'   => array('0' => 'Pendiente', 
                                                            '1' => 'Activo',
                                                            '2' => 'Bloqueado'),
                                    'required'  => true,
                                ))
            ->add('fecha', 'date', 
                    array(
                        'widget' => 'single_text',
                        'format' => 'dd-MM-yyyy HH:mm:ss',
                        'attr'      => array('class' => 'col-md-3'),
                    )
            )
            ->add('idRole', 'entity',
                    array(
                        'class' => 'AacBundle:Role',
                        'label' => 'Role',
                        'property' => 'descripcion',
                        'attr'      => array('class' => 'col-md-3'),
                    )
            )
            ->add('duracion', 'integer', array(
                'attr'      => array('class' => 'col-md-1'),
                'label'     => 'Duraciónn en Minutos'
            ));

    }    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aac\AacBundle\Entity\Usuarios',
            'csrf_protection' => FALSE,
            'csrf_field_name' => '_token',
        ));
    }

    public function getName()
    {
        return 'Usuarios';
    }
}
