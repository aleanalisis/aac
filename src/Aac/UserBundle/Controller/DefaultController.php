<?php

namespace Aac\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {

        return $this->render('AacUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
