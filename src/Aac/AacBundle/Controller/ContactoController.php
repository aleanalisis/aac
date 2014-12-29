<?php
// Aac\AacBundle\Controller\ContactoController.php
namespace Aac\AacBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Aac\AacBundle\Entity\Contacto;
use Aac\AacBundle\Form\ContactoType;

class ContactoController extends Controller {
    
    public function listaAction(Request $request) 
    {
        $servicios = $this->get('Servicios');
        
        if (false === $this->get('security.context')->isGranted('ROLE_INTERVENTOR')){
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'NO AUTORIZADO!',
                    'message' => 'No estás autorizado para entrar en esta sección'
                )
            );            
            $parametros['modal'] = $servicios->modalContacto();
            return $this->render('AacBundle:Default:index.html.twig', $parametros);
        }
        
        $user = $this->getUser();
        $nivelUser = $user->getNivel();
        
        $contactos = $this->getDoctrine()->getRepository('AacBundle:Contacto')->buscarTodos();            
        
        if (!$contactos) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! No hay contactos para mostrar.',
                )
            );            
            return $this->redirect($this->generateUrl('aac/inicio'));
        }
        
        // Añadimos el paginador (En este caso el parámetro "1" es la página actual, 
        // y parámetro "10" es el número de páginas a mostrar)
        
        $paginator  = $this->get('knp_paginator');
        
        $pagination = $paginator->paginate(
            $contactos,
            $this->get('request')->query->get('page', 1),4
        );        
        
        $parametros['entities'] = $contactos;
        $parametros['pagination'] = $pagination;
        $parametros['modal'] = $servicios->modalContacto();
        
        $parametros['titulo'] = 'Listado de Contactos';
        return $this->render('AacBundle:Contacto:lista_contactos.html.twig', $parametros);
    }
    
    public function contactoAction (Request $request)
    {
        $contacto = new Contacto();
        
        $form = $this->createForm(new ContactoType(), $contacto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            
            $em->persist($contacto);
            
            $contacto->setFecha(new \DateTime("now"));
            
            $em->flush();
            
            $this->get('session')->getFlashBag()->set(
                'success',
                array(
                    'title' => 'PETICIÓN !!!! En breve el personal de AAC se pondrá en contacto con Usted.',
                )
            );            
            return $this->redirect($this->generateUrl('aac/inicio'));            
        }    
        $parametros['form'] = $form->createView();        
        $parametros['titulo'] = 'Petición de Consulta';
        
        return $this->render('AacBundle:Contacto:contacto.html.twig', $parametros);
       
    }    
    public function verAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_INTERVENTOR')){
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'NO AUTORIZADO!',
                    'message' => 'No estás autorizado para entrar en esta sección'
                )
            );            
            return $this->render('AacBundle:Default:index.html.twig');
        }
        
        $contactos = $this->getDoctrine()->getRepository('AacBundle:Contacto')->buscarId($id);

        if (!$contactos) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! CONTACTO INEXISTENTE',
                    'message' => 'El contacto ha ver no existe.'
                )
            );            
            return $this->redirect($this->generateUrl('contacto_lista'));            
        }
        $form = $this->createForm(new ContactoType(), $contactos, array(
                'empty_data' => 'ver'));
        
        $parametros['form'] = $form->createView();
        $parametros['usuarios'] = $contactos;
        $parametros['titulo'] = 'Detalle de Contacto';
        
        return $this->render('AacBundle:Contacto:ver.html.twig', $parametros);        
    }
    public function eliminarAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_INTERVENTOR')){
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'NO AUTORIZADO!',
                    'message' => 'No estás autorizado para entrar en esta sección'
                )
            );            
            return $this->render('AacBundle:Default:index.html.twig');
        }
        
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('AacBundle:Contacto')->buscarId($id);

        if (!$contacto) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! CONTACTO INEXISTENTE',
                    'message' => 'El contacto ha eliminar no existe.'
                )
            );            
            return $this->redirect($this->generateUrl('contacto_lista'));
        }
        
        $em->remove($contacto);
        $em->flush();
        
        $this->get('session')->getFlashBag()->set(
            'success',
            array(
                'title' => 'ELIMINADO!',
                'message' => 'El Contacto "' . $contacto->getNombre() . '" ha sido eliminado satisfactoriamente.'
            )
        );

        return $this->redirect($this->generateUrl('contacto_lista'));        
    }
}