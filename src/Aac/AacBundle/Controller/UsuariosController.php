<?php
// Aac\AacBundle\Controller\UsuariosController.php

namespace Aac\AacBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Aac\UserBundle\Entity\User;
use Aac\UserBundle\Form\Type\ModificarFormType;
use Aac\AacBundle\Form\SeleccionarType;
/**
 * Usuarios controller.
 *
 * @Route("/usuarios")
 */
class UsuariosController extends Controller
{
    public function inicioAction()
    {
        $nombreArchivo = __DIR__.'/../Resources/doc/ArchivoSql.txt';
        
        if (file_exists($nombreArchivo)) {
            unlink($nombreArchivo);
        }
        return $this->redirect($this->generateUrl('usuarios'));
    }

    public function indexAction(Request $request) 
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
        
        $user = $this->getUser();
        $nivelUser = $user->getNivel();

        if (!empty($_POST)){
            $data = $_POST;
            
            //Cambiar formato fecha a aa-mm-yy 
            // si está en blanco le damos valor 2014
            if($data['fecha_desde'] !== ''){
                $date = new \DateTime($data['fecha_desde']);
                $fecha_desde =  $date->format('Y-m-d H:i:s');
            }else{
                $date = new \DateTime('01-01-2014');
                $fecha_desde =  $date->format('Y-m-d H:i:s');                
            }
            
            if($data['fecha_hasta'] !== ''){
                $date = new \DateTime($data['fecha_hasta']);
                $fecha_hasta =  $date->format('Y-m-d H:i:s');
            }else{
                $date = new \DateTime('31-12-2035');
                $fecha_hasta =  $date->format('Y-m-d H:i:s');                
            }            
            // Asignar valores a $data
            $data['fecha_desde'] = $fecha_desde;
            $data['fecha_hasta'] = $fecha_hasta;
            $data['nivel'] = $nivelUser;
            // generar archivo con los datos de la consulta
            $this->crearArchivoSql($data);
        }
        
        $nombreArchivo = __DIR__.'/../Resources/doc/ArchivoSql.txt';
        
        if (file_exists($nombreArchivo)) {
            ;
            $file = fopen($nombreArchivo, "r");
            while(!feof($file)) {
                
                $key = substr(fgets($file), 0, -1);
                $valor =  substr(fgets($file), 0, -1);
                
                $data[$key] = $valor;
            }
            $data11 = array_pop($data);
            fclose($file);
            $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')
                    ->buscarSeleccionados($data);            
        }else{
            $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')
                    ->buscarPorNivel($nivelUser);            
        }
        
        if (!$usuarios) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! No hay usuarios para mostrar.',
                )
            );            
            return $this->redirect($this->generateUrl('aac/inicio'));
        }
        
        // Añadimos el paginador (En este caso el parámetro "1" es la página actual, 
        // y parámetro "10" es el número de páginas a mostrar)
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $usuarios,
            $this->get('request')->query->get('page', 1),4
        );        
        
        $parametros['entities'] = $usuarios;
        $parametros['pagination'] = $pagination;
        $parametros['modal'] = $servicios->modalUsuario();
        $parametros['titulo'] = 'Usuarios en activo';
        return $this->render('AacBundle:Usuarios:lista_usuarios.html.twig', $parametros);
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
        
        return $this->render('AacBundle:Usuarios:ver.html.twig', $parametros);        
    }
    
    public function modificarAction(Request $request, $id)
    {
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
        
        $password = $usuarios->getPassword();
        $editForm = $this->createForm(new ModificarFormType(), $usuarios, array(
                'empty_data' => $this->getUser()->getNivel()));
        $role = $usuarios->getRoles();
        $usuarios->removeRole($role[0]);

        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($usuarios);
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
        
        return $this->render('AacBundle:Usuarios:modificar.html.twig', $parametros);
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

    public function crearArchivoSql($data)
    {
        // Crear archivo con el array que contiene 
        // el Email y el username a enviar
        $user = $this->getUser();
        $nivelUser = $user->getNivel();

        $nombreArchivo = __DIR__.'/../Resources/doc/ArchivoSql.txt';
        if (file_exists($nombreArchivo)) {
            unlink($nombreArchivo);
        }            
        $file = fopen($nombreArchivo,"w");
        foreach($data as $clave => $generar) {
            if($clave !== 'submit'){
                fputs($file, $clave);
                fputs($file,"\n");
                fputs($file, $generar);
                fputs($file,"\n");
            }
        }
        if($file){
            fclose($file);
        }        
        
        return ;
    }    
}
