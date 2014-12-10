<?php

namespace Aac\AacBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AacBundle:Default:index.html.twig', array('name' => $name));
    }

    public function inicioAction()
    {
        //Para acceder a la variable global desde twig seria:
        //<p>La Variable es: {{ miVariable }}</p>
        // acceder a los parametros de parameter.yml
        //$secret = $this->container->getParameter('secret');

        $servicios = $this->get('Servicios');
        //$pass = $this->get('My_Hash');
        //$password = $pass->getHash('sha512', '0501120608', $this->container->getParameter('secret'));        
        //var_dump($password);exit;
        $parametros['modal'] = $servicios->modalUsuario();
        return $this->render('AacBundle:Default:inicio.html.twig', $parametros);
    }
}
