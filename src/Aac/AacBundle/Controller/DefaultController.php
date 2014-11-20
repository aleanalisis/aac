<?php

namespace Aac\AacBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $error = $this->get('kernel.listener.your_listener_name');
        print_r($error);exit;
        return $this->render('AacBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function inicioAction()
    {
        //Para acceder a la variable global desde twig seria:
        //<p>La Variable es: {{ miVariable }}</p>
        // acceder a los parametros de parameter.yml
        //$secret = $this->container->getParameter('secret');
        
        // Encriptar la contraseña
        $modal = $this->variablesModal('usuarios');
        //$pass = $this->get('My_Hash');
        //$password = $pass->getHash('sha512', '0501120608', $this->container->getParameter('secret'));        
        //var_dump($password);exit;
        $parametros['modal'] = $modal;
        return $this->render('AacBundle:Default:inicio.html.twig', $parametros);
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
        $modal['url_base'] = '';

        return $modal;
    }    
}
