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
        $servicios = $this->get('Servicios');
        $user = $this->getUser();

        //$csrf_provider = $this->container->get('form.csrf_provider');
        //$csrfToken = $csrf_provider->generateCsrfToken('');
        //print_r($csrfToken);exit;
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
        $parametros['modal'] = $servicios->modalUsuario();
        $parametros['titulo'] = 'Enviar Email a : ' . $usuarios->getNombre();
        return $this->render('AacBundle:Email:index.html.twig', $parametros);
    }
    
    public function enviarAction(Request $request, $id)
    {
        $servicios = $this->get('Servicios');
        $user = $this->getUser();

        //$csrf_provider = $this->container->get('form.csrf_provider');
        //$csrfToken = $csrf_provider->generateCsrfToken('');
        //print_r($csrfToken);exit;
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
        $parametros['modal'] = $servicios->modalUsuario();
        $parametros['titulo'] = 'Enviar Email a : ' . $usuarios->getNombre();
        return $this->render('AacBundle:Email:enviar.html.twig', $parametros);
    }

    public function seleccionAction(Request $request)
    {

        $servicios = $this->get('Servicios');
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
            $parametros['modal'] = $servicios->modalUsuario();
            return $this->render('AacBundle:Default:index.html.twig', $parametros);
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
        
        $parametros['checked'] = 'checked';
        $parametros['entities'] = $usuarios;
        $parametros['modal'] = $servicios->modalUsuario();
        $parametros['titulo'] = 'Selección de usuarios para enviar Email';
        
        return $this->render('AacBundle:Email:seleccion_email.html.twig', $parametros);
    }
    
    public function textoAction(Request $request)
    {
        $servicios = $this->get('Servicios');
        $user = $this->getUser();
        
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
            fclose($file);
        }else{
            
            $this->crearIdTo();
        }        
        
        //$csrf_provider = $this->container->get('form.csrf_provider');
        //$csrfToken = $csrf_provider->generateCsrfToken('');
        //print_r($csrfToken);exit;

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
                $em->persist($email);
                //$email = new Email();
                $email->setDe($user->getId());
                $email->setPara($ids);
                
                $email->setAsunto($data->getAsunto());
                
                $email->setCabecera($data->getCabecera());
                
                $email->setTexto($data->getTexto());
                
                $email->setFecha(new \DateTime("now"));
                
                $email->setEnviado(TRUE);
                
                $email->setMasivo(TRUE);
                
                $em = $this->getDoctrine()->getManager();

                //$em->flush();
            }
            
            // Recuperar el servicio EmailsServicio
           
            //var_dump($envioTo);exit;
            $enviarEmail = $this->get('EmailsServicio');
            $enviarEmail->envioEmails($data, $user->getEmail(), $envioTo);

            $this->get('session')->getFlashBag()->set(
                'success',
                array(
                    'title' => 'Enviado !!!!',
                    'message' => 'Los E-mails se han enviado con exito.'
                )
            );            
            return $this->redirect($this->generateUrl('usuarios'));            
        }    

        $parametros['form'] = $form->createView();        
        $parametros['modal'] = $servicios->modalUsuario();
        $parametros['titulo'] = 'Enviar Email masivo';
        return $this->render('AacBundle:Email:texto.html.twig', $parametros);
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
}

