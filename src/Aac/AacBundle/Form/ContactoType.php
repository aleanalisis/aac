<?php
// Aac\AacBundle\Form\ContactoType.php
namespace Aac\AacBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactoType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $disabled = false;
        if (array_key_exists('empty_data', $options)) {
            if($options['empty_data'] == 'ver'){
                $disabled = true;
            }
        }
        $builder
                ->add('empresa', 'text',    array(
                    'attr'          =>  array('class'   =>  'col-md-4',
                    'disabled'      =>  $disabled,
                    'autofocus'     =>  'autofocus'                        
                    ),
                    'label_attr'    =>  array('class'   =>  'col-md-2 control-label text-right'),
                    'label'         =>  'Empresa : '
                ))
                ->add('nombre', 'text',     array(
                    'attr'          =>  array('class'   =>  'col-md-4',
                    'disabled'      =>  $disabled
                    ),
                    'label_attr'    =>  array('class'   =>  'col-md-2 control-label text-right'),
                    'label'         =>  'Nombre : '
                ))
                ->add('email', 'email',     array(
                    'attr'          =>  array('class'   =>  'col-md-4',
                    'disabled'      =>  $disabled
                    ),
                    'label_attr'    =>  array('class'   =>  'col-md-2 control-label text-right'),
                    'label'         =>  'E-Mail : '
                ))
                ->add('telefono', 'text',   array(
                    'attr'          =>  array('class'   =>  'col-md-2',
                    'disabled'      =>  $disabled,
                    'maxlength'     =>  9    
                    ),
                    'required'      => FALSE,
                    'label_attr'    =>  array('class'   =>  'col-md-2 control-label text-right'),
                    'label'         =>  'Teléfono : '
                ))
                ->add('texto', 'textarea',  array(
                    'attr'          =>  array('class'   =>  'col-md-5',
                    'disabled'      =>  $disabled,
                    'rows'          =>  8
                    ),
                    'label_attr'    =>  array('class'   =>  'col-md-2 control-label text-right'),
                    'label'         =>  'Texto : '                    
                ))
                ->add('captcha', 'captcha', array(
                    'label_attr'    =>  array('class'   =>  'col-md-2 control-label text-right'),
                    'label'         =>  'Captcha : ',
                    'width'         =>  100,
                    'height'        =>  30,
                    'length'        =>  5,
                    'quality'       =>  500,
                    'distortion'    =>  false
                ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aac\AacBundle\Entity\Contacto',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
        ));
    }

    public function getName()
    {
        return 'Contacto';
    }    
}
