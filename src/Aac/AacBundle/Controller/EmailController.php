<?php
// src/Aac/AacBundle/Controller/EmailController.php

namespace Aac\AacBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Aac\UserBundle\Entity\User;
use Aac\AacBundle\Entity\Email;
use Aac\AacBundle\Form\EmailType;
use Aac\AacBundle\Form\EnvioType;

Class EmailController extends Controller 
{
    
    public function indexAction($id)
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
        
        $user = $this->getUser();

        $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')->find($id);
        $emailPara = $usuarios->getEmail();

        if (!$usuarios) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! USUARIO INEXISTENTE',
                    'message' => 'El receptor del Email no existe.'
                )
            );
            return $this->redirect($this->generateUrl('usuarios'));
        }

        $email = new Email();
        $form = $this->createForm(new EmailType(), $email);

        $parametros['form'] = $form->createView();        
        $parametros['entities'] = $email;
        $parametros['titulo'] = 'Enviar Email a : ' . $usuarios->getNombre();
        return $this->render('AacBundle:Email:index.html.twig', $parametros);
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
        
        $emails = $this->getDoctrine()->getRepository('AacBundle:Email')->findBuscarPorUsuario($id);
        
        if (!$emails) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! USUARIO INEXISTENTE',
                    'message' => 'El E-mail a ver no existe.'
                )
            );            
            return $this->redirect($this->generateUrl('fos_user_security_logout'));            
        }
        // $para lo guardo para poder volver a la lista de ese id
        $para = $emails->getPara();
        $parametros['para'] = $para;
        
        $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')->find(
                $emails->getDe());
        $emails->setDe($usuarios->getEmail());
        
        $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')->find(
                $emails->getPara());
        $emails->setPara($usuarios->getEmail());
        
        $form = $this->createForm(new EmailType(), $emails, array(
                'empty_data' => 'ver'));        

        $parametros['form'] = $form->createView();
        $parametros['emails'] = $emails;
        $parametros['titulo'] = 'Detalle de E-Mail';
        return $this->render('AacBundle:Email:ver.html.twig', $parametros);        
    }
    
    public function enviarAction(Request $request, $id)
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
        $user = $this->getUser();

        $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')->find($id);
               
        if (!$usuarios) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! USUARIO INEXISTENTE',
                    'message' => 'El receptor del Email no existe.'
                )
            );
            return $this->redirect($this->generateUrl('usuarios'));
        }

        $email = new Email();
        $email->setDe($user->getEmail());
        $email->setPara($usuarios->getEmail());        
        
        $form = $this->createForm(new EmailType(), $email);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();

            $email->setDe($user->getId());
            $email->setPara($usuarios->getId());
            
            $em->persist($email);
            
            $email->setDe($user->getId());
            $email->setPara($usuarios->getId());
            $email->setFecha(new \DateTime("now"));
            $email->setEnviado(true);
            $email->setMasivo(false);
            
            $em->flush();
            
            // Recuperar el servicio EmailsServicio
            $envioTo = array($usuarios->getEmail()   => $usuarios->getUsername());
            $enviarEmail = $this->get('EmailsServicio');
            $enviarEmail->envioEmails($data, $user->getEmail(), $envioTo);
            
            $this->get('session')->getFlashBag()->set(
                'success',
                array(
                    'title' => 'Enviado !!!!',
                    'message' => 'El E-mail para "' . $usuarios->getEmail() . '" se ha enviado con exito.'
                )
            );            
            return $this->redirect($this->generateUrl('usuarios'));            
        }    

        $parametros['form'] = $form->createView();        
        $parametros['titulo'] = 'Enviar Email a : ' . $usuarios->getNombre();
        
        return $this->render('AacBundle:Email:enviar.html.twig', $parametros);
    }

    public function seleccionAction(Request $request, $activo)
    {
        
        $nombreArchivoTo = __DIR__.'/../Resources/doc/SeleccionTo.txt';
        $nombreArchivoId = __DIR__.'/../Resources/doc/SeleccionId.txt';        
        // Borrar archivos de selección
        if (file_exists($nombreArchivoTo)) {
            unlink($nombreArchivoTo);
        }
        if (file_exists($nombreArchivoId)) {
            unlink($nombreArchivoId);
        }        
        
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
        $user = $this->getUser();
        $nivelUser = $user->getNivel();
        
        $em = $this->getDoctrine()->getManager();
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

        $parametros['activo'] = $activo;
        $parametros['entities'] = $usuarios;
        $parametros['titulo'] = 'Selección de usuarios para enviar Email';
        
        return $this->render('AacBundle:Email:seleccion_email.html.twig', $parametros);
    }
    
    public function textoAction(Request $request)
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
        $user = $this->getUser();
        
        $elementos = count($_POST);

        if ($elementos <= 1){
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! NO HAY USUARIOS SELECCIONADOS',
                    'message' => ''
                )
            );
            return $this->redirect($this->generateUrl('enviar_email_todos', array('activo' => '1')));            
        }
        
        $nombreArchivoTo = __DIR__.'/../Resources/doc/SeleccionTo.txt';
        $nombreArchivoId = __DIR__.'/../Resources/doc/SeleccionId.txt';
        
        if (file_exists($nombreArchivoTo)) {
            $file = fopen($nombreArchivoTo, "r");
            while(!feof($file)) {
                
                $key = substr(fgets($file), 0, -1);
                $valor =  substr(fgets($file), 0, -1);
                
                $envioTo[$key] = $valor;
            }
            $envioTo1 = array_pop($envioTo);
            fclose($file);
        }else{
            $this->crearEnvioTo();
        }
        if (file_exists($nombreArchivoId)) {
            $file = fopen($nombreArchivoId, "r");
            while(!feof($file)) {
                $idTo[] = fgets($file);
            }
            $vidTo1 = array_pop($idTo);
            fclose($file);
        }else{
            
            $this->crearIdTo();
        }        
        
        $email = new Email();
        $email->setDe($user->getEmail());
        
        $form = $this->createForm(new EmailType(), $email);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $data->setDe($user->getId());

            foreach($idTo as $ids){

                $data->setPara($ids);
                $data->setFecha(new \DateTime("now"));
                $data->setEnviado(true);
                $data->setMasivo(true);
                $email = new Email();
                $email->setDe($user->getEmail());                
                $em->persist($email);

                $email->setDe($user->getId());
                $email->setPara($ids);
                
                $email->setAsunto($data->getAsunto());
                
                $email->setCabecera($data->getCabecera());
                
                $email->setTexto($data->getTexto());
                
                $email->setFecha(new \DateTime("now"));
                
                $email->setEnviado(TRUE);
                
                $email->setMasivo(TRUE);
                
                $em = $this->getDoctrine()->getManager();

                $em->flush();
            }
            // Recuperar el servicio EmailsServicio
            $enviarEmail = $this->get('EmailsServicio');
            $enviarEmail->envioEmails($data, $user->getEmail(), $envioTo);
            // Borrar archivos de apoyo
            if (file_exists($nombreArchivoTo)) {
                unlink($nombreArchivoTo);
            }
            if (file_exists($nombreArchivoId)) {
                unlink($nombreArchivoId);
            }
            $this->get('session')->getFlashBag()->set(
                'success',
                array(
                    'title' => 'ENVIADOS !!!!',
                    'message' => 'Los E-mails se han enviado con exito.'
                )
            );            
            return $this->redirect($this->generateUrl('usuarios'));            
        }    

        $parametros['form'] = $form->createView();
        $parametros['titulo'] = 'Enviar Email masivo';
        return $this->render('AacBundle:Email:texto.html.twig', $parametros);
    }

    public function listaAction(Request $request, $id)
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
        // Recuperar datos del usuario logeado
        $user = $this->getUser();
        $idUser = $user->getId();
       
        $email = $this->getDoctrine()->getRepository('AacBundle:Email')
                    ->findBuscarPorUsuario($id);           
        
        if (!$email) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'NO EXISTE!',
                    'message' => 'No existe ningún E-mail para este usuario.'
                )
            );            
            return $this->redirect($this->generateUrl('usuarios'));
        }
        
        $lista1 = array();
        foreach ($email as $value) {
            $lista1[] = $value;
        }
        
        // Crear lista cambiando los nombres 'de' y 'para'
        $lista = $this->crearLista($lista1);
        
        // Añadimos el paginador (En este caso el parámetro "1" es la página actual, 
        // y parámetro "5" es el número de páginas a mostrar)
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
             $lista,
            $this->get('request')->query->get('page', 1),5
        );        
        $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')
                ->find($id);
        $para = $usuarios->getNombre();
        $parametros['para'] = $para;
        $parametros['entities'] = $lista;
        $parametros['pagination'] = $pagination;
        $parametros['titulo'] = 'Emails enviados';
        
        return $this->render('AacBundle:Email:lista_emails.html.twig', $parametros);        
    }
    
    public function crearEnvioTo()
    {
        // Crear archivo con el array que contiene 
        // el Email y el username a enviar

        if (count($_POST) > 1) {
            $user = $this->getUser();
            $nivelUser = $user->getNivel();

            $em = $this->getDoctrine()->getManager();
            $nombreArchivo = __DIR__.'/../Resources/doc/SeleccionTo.txt';
            if (file_exists($nombreArchivo)) {
                unlink($nombreArchivo);
            }            
            $file = fopen($nombreArchivo,"w");
            foreach($_POST as $enviar) {
               
                if($enviar[0] !== 'submit') {
                    $usuarios = $this->getDoctrine()
                            ->getRepository('AacUserBundle:User')
                            ->find($enviar[0]);

                    fputs($file, $usuarios->getEmail());
                    fputs($file,"\n");
                    fputs($file, $usuarios->getUsername());
                    fputs($file,"\n");                    
                }
            }
        }else {
            return $this->redirect($this->generateUrl('usuarios'));
        }

        if($file){
            fclose($file);
        }        
        
        return ;
    }
    
    public function crearIdTo()
    {
        // Crear archivo con el array que contiene 
        // el id del usuario a enviar        
        if (count($_POST) > 1) {
            $nombreArchivo = __DIR__.'/../Resources/doc/SeleccionId.txt';
            if (file_exists($nombreArchivo)) {
                unlink($nombreArchivo);
            }            
            $file_open = fopen($nombreArchivo,"w");
            foreach($_POST as $enviar) {
                
                if($enviar[0] !== 'submit') {
                    
                    if($file_open){
                        fwrite($file_open, $enviar[0] . "\r\n");
                    }                    
                }
            }
        }else {
            return $this->redirect($this->generateUrl('usuarios'));
        }
        
        if($file_open){
            fclose($file_open);
        }
        return ;        
    }

    public function crearLista($lista1)
    {
        $lista = array();
        for($i = 0; $i < count($lista1); $i++){

            $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')
                    ->find($lista1[$i]->getDe());
            if (!$usuarios) {
                //throw $this->createNotFoundException('El Usuario - ' . $id . ' - no existe.');
            }else{
            $lista[$i]['id'] = $lista1[$i]->getId();
            $lista[$i]['asunto'] = $lista1[$i]->getAsunto();
            $lista[$i]['cabecera'] = $lista1[$i]->getCabecera();                
            $lista[$i]['de'] = $lista1[$i]->getDe();
            $lista[$i]['para'] = $lista1[$i]->getPara();
            $lista[$i]['fecha'] = $lista1[$i]->getFecha();
            $lista[$i]['nombreDe'] = $usuarios->getNombre();
            }
        }
        return $lista;
    }
    
}

