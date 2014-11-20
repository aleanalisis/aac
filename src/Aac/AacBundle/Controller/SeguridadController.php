<?php
// src/Aac/AacBundle/Controller/SeguridadController.php;
namespace Aac\AacBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Aac\AacBundle\Entity\Usuarios;
use Aac\AacBundle\Form\LoginType;
 
class SeguridadController extends Controller
{
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        $modal = $this->variablesModal('usuarios');

        $user = new Usuarios();
        $user->setUsername = 'usuario';
        //$user->setSalt(uniqid(mt_rand())); // Unique salt for user

        // Set encrypted password
        $encoder = $this->container->get('My_Hash');

        $pass = $encoder->encodePassword('0164', $this->container->getParameter('secret'));
        $user->setPassword($pass);

        $form = $this->createForm(new LoginType(), $user);
        $request = $this->getRequest();
        $session = $request->getSession();
        
        // get the login error if there is one
        $bien = $encoder->isPasswordValid($pass, '0164', $this->container->getParameter('secret'));
        
            

            if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
                $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        $parametros['form'] = $form->createView();
        $parametros['titulo'] = 'Registro de Usuarios';
        $parametros['modal'] = $modal;
        $parametros['last_username'] = $session->get(SecurityContextInterface::LAST_USERNAME);
        $parametros['error'] = $error;
        return $this->render(
            'AacBundle:Seguridad:login.html.twig', $parametros);
    }

    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }
 
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
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
