<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Aac\AacBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Aac\UserBundle\Entity\User;
use Aac\UserBundle\Form\Type\ModificarFormType;

/**
 * Usuarios controller.
 *
 * @Route("/usuarios")
 */
class UsuariosController extends Controller
{

    public function indexAction() {

        $servicios = $this->get('Servicios');
       
        if (false === $this->get('security.context')->isGranted('ROLE_INTERVENTOR')){
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'NO AUTORIZADO!',
                    'message' => 'No estás autorizado para entrar en esta sección'
                )
            );            
            $parametros['modal'] = $servicios->modalUsuario();
            return $this->render('AacBundle:Default:index.html.twig', $parametros);
        }
        $user = $this->getUser();
        $nivelUser = $user->getNivel();
        
        $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')->buscarPorNivel($nivelUser);
        
        if (!$usuarios) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! No hay usuarios para mostrar.',
                )
            );            
            return $this->redirect($this->generateUrl('aac/inicio'));
        }
        
        // Añadimos el paginador (En este caso el parámetro "1" es la página actual, y parámetro "10" es el número de páginas a mostrar)
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $usuarios,
            $this->get('request')->query->get('page', 1),5
        );        
        
        $parametros['entities'] = $usuarios;
        $parametros['pagination'] = $pagination;
        $parametros['modal'] = $servicios->modalUsuario();

        $parametros['titulo'] = 'Usuarios en activo';
        return $this->render('AacBundle:Usuarios:lista_usuarios.html.twig', $parametros);
    }

    public function bloqueadosAction() {

        $servicios = $this->get('Servicios');
       
        if (false === $this->get('security.context')->isGranted('ROLE_INTERVENTOR')){
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'NO AUTORIZADO!',
                    'message' => 'No estás autorizado para entrar en esta sección'
                )
            );            
            $parametros['modal'] = $servicios->modalUsuario();
            return $this->render('AacBundle:Default:index.html.twig', $parametros);
        }
        $user = $this->getUser();
        $nivelUser = $user->getNivel();
        
        $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')->buscarBloqueados();
        
        if (!$usuarios) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! No hay usuarios para mostrar.',
                )
            );            
            return $this->redirect($this->generateUrl('aac/inicio'));
        }
        
        // Añadimos el paginador (En este caso el parámetro "1" es la página actual, y parámetro "10" es el número de páginas a mostrar)
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $usuarios,
            $this->get('request')->query->get('page', 1),5
        );        
        
        $parametros['entities'] = $usuarios;
        $parametros['pagination'] = $pagination;
        $parametros['modal'] = $servicios->modalUsuario();

        $parametros['titulo'] = 'Usuarios en activo';
        return $this->render('AacBundle:Usuarios:lista_usuarios.html.twig', $parametros);
    }
    
    public function verAction($id)
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
            $parametros['modal'] = $servicios->modalUsuario();
            return $this->render('AacBundle:Default:index.html.twig', $parametros);
        }
        
        $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')->find($id);

        if (!$usuarios) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! USUARIO INEXISTENTE',
                    'message' => 'El Usuario ha ver no existe.'
                )
            );            
            return $this->redirect($this->generateUrl('fos_user_security_logout'));            
        }
        $roles = $usuarios->getRoles();
        $form = $this->createForm(new ModificarFormType(), $usuarios);
        
        $parametros['form'] = $form->createView();
        $parametros['usuarios'] = $usuarios;
        $parametros['titulo'] = 'Detalle de Usuarios';
        $parametros['modal'] = $servicios->modalUsuario();
        
        return $this->render('AacBundle:Usuarios:ver.html.twig', $parametros);        
    }
    
    public function modificarAction(Request $request, $id)
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
            $parametros['modal'] = $servicios->modalUsuario();
            return $this->render('AacBundle:Default:index.html.twig', $parametros);
        }
        
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('AacUserBundle:User')->find($id);

        if (!$usuarios) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! USUARIO INEXISTENTE',
                    'message' => 'El Usuario ha ver no existe.'
                )
            );            
            return $this->redirect($this->generateUrl('fos_user_security_logout'));
        }
        
        //$fecha = $usuarios->getFecha();
        $password = $usuarios->getPassword();
        $editForm = $this->createForm(new ModificarFormType(), $usuarios);
        $role = $usuarios->getRoles();
        $usuarios->removeRole($role[0]);

        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($usuarios);
            //$usuarios->setFecha($fecha);
            $usuarios->setPassword($password);
            $em->flush();
            $this->get('session')->getFlashBag()->set(
                'success',
                array(
                    'title' => 'Modificado!',
                    'message' => 'El Usuario "' . $usuarios->getUsername() . '" ha sido actualizado satisfactoriamente.'
                )
            );            
            return $this->redirect($this->generateUrl('usuarios'));
        }
        
        $parametros['form'] = $editForm->createView();
        $parametros['usuarios'] = $usuarios;
        $parametros['titulo'] = 'Modificación de Usuarios';
        $parametros['modal'] = $servicios->modalUsuario();
        
        return $this->render('AacBundle:Usuarios:modificar.html.twig', $parametros);
    }
    
    public function eliminarAction($id){
        
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('AacUserBundle:User')->find($id);

        if (!$usuarios) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! USUARIO INEXISTENTE',
                    'message' => 'El Usuario ha eliminar no existe.'
                )
            );            
            return $this->redirect($this->generateUrl('usuarios'));
        }
        
        $em->remove($usuarios);
        $em->flush();
        
        $this->get('session')->getFlashBag()->set(
            'success',
            array(
                'title' => 'ELIMINADO!',
                'message' => 'El Usuario "' . $usuarios->getUsername() . '" ha sido eliminado satisfactoriamente.'
            )
        );

        return $this->redirect($this->generateUrl('usuarios'));        
    }
}
