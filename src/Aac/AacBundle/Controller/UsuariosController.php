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
    
        $modal = $this->variablesModal('usuarios');

        if (false === $this->get('security.context')->isGranted('ROLE_INTERVENTOR')){
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'NO AUTORIZADO!',
                    'message' => 'No estás autorizado para entrar en esta sección'
                )
            );            
            $parametros['modal'] = $modal;
            return $this->render('AacBundle:Default:index.html.twig', $parametros);
        }
        
        $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')->findAll();

        if (!$usuarios) {
            throw $this->createNotFoundException('No hay Usuarios.');
        }
        
        // Añadimos el paginador (En este caso el parámetro "1" es la página actual, y parámetro "10" es el número de páginas a mostrar)
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $usuarios,
            $this->get('request')->query->get('page', 1),4
        );        
        
        $parametros['entities'] = $usuarios;
        $parametros['pagination'] = $pagination;
        $parametros['modal'] = $modal;

        $parametros['titulo'] = 'Usuarios en activo';
        return $this->render('AacBundle:Usuarios:lista_usuarios.html.twig', $parametros);
    }
    
    public function verAction($id)
    {
        $modal = $this->variablesModal('usuarios');
        $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')->find($id);

        if (!$usuarios) {
            throw $this->createNotFoundException('El Usuario - ' . $id . ' - no existe.');
        }
        $roles = $usuarios->getRoles();
        $form = $this->createForm(new ModificarFormType(), $usuarios);
        
        $parametros['form'] = $form->createView();
        $parametros['usuarios'] = $usuarios;
        $parametros['titulo'] = 'Detalle de Usuarios';
        $parametros['modal'] = $modal;
        
        return $this->render('AacBundle:Usuarios:ver.html.twig', $parametros);        
    }
    
    public function modificarAction(Request $request, $id)
    {
        $modal = $this->variablesModal('usuarios');
        $em = $this->getDoctrine()->getEntityManager();
        $usuarios = $em->getRepository('AacUserBundle:User')->find($id);

        if (!$usuarios) {
            throw $this->createNotFoundException('El Usuario - ' . $id . ' - no existe.');
        }
        
        //$fecha = $usuarios->getFecha();
        $password = $usuarios->getPassword();
        $editForm = $this->createForm(new ModificarFormType(), $usuarios);
        $role = $usuarios->getRoles();
        $usuarios->removeRole($role[0]);

        $editForm->handleRequest($request);
        
        if ($editForm->isValid()) {
            $em->persist($usuarios);
            //$usuarios->setFecha($fecha);
            $usuarios->setPassword($password);
            $em->flush();
            $this->get('session')->getFlashBag()->set(
                'success',
                array(
                    'title' => 'Modificado!',
                    'message' => 'Usuario actualizado satisfactoriamente.'
                )
            );            
            return $this->redirect($this->generateUrl('usuarios'));
        }
        
        $parametros['form'] = $editForm->createView();
        $parametros['usuarios'] = $usuarios;
        $parametros['titulo'] = 'Modificación de Usuarios';
        $parametros['modal'] = $modal;
        
        return $this->render('AacBundle:Usuarios:modificar.html.twig', $parametros);
    }
    
    public function eliminarAction($id){
        
        $modal = $this->variablesModal('usuarios');
        $em = $this->getDoctrine()->getEntityManager();
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
                'message' => 'El Usuario ha sido eliminado satisfactoriamente.'
            )
        );

        return $this->redirect($this->generateUrl('usuarios'));        
    }
    
    public function variablesModal($activo){

        //$this->layout()->setVariable($activo, 'active');

        //$url = $this->getRequest()->getBaseUrl();

        $modal['id'] = 'eliminarUsuario';
        $modal['message'] = '¿ Realmente desea eliminar este registro ?';
        $modal['href_cancel'] = 'usuarios';
        $modal['href_action'] = 'usuarios_eliminar';
        $modal['param'] = '';
        $modal['text_btn'] = 'Eliminar';
        $modal['url_base'] = '/usuarios';

        return $modal;
    }    
}
