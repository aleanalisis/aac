<?php
// Aac\AacBundle\Controller\DefaultController.php
namespace Aac\AacBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Aac\AacBundle\Entity\Contacto;
use Aac\AacBundle\Form\ContactoType;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AacBundle:Default:index.html.twig', array('name' => $name));
    }

    public function inicioAction()
    {
        return $this->render('AacBundle:Default:inicio.html.twig');
    }
    
    public function profesionalesAction()
    {
        return $this->render('AacBundle:Default:profesionales.html.twig');
    }
    
    public function voluntariosAction()
    {
        return $this->render('AacBundle:Default:voluntarios.html.twig');
    }    

    public function otrosAction()
    {
        return $this->render('AacBundle:Default:otros.html.twig');
    }
    
}
