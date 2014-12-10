<?php
/**
 * Description of ArchivoController
 */

namespace Aac\AacBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Aac\UserBundle\Entity\User;
use Aac\AacBundle\Entity\Archivo;
use Aac\AacBundle\Form\ArchivoType;

/**
 * Archivo controller.
 *
 * @Route("/usuarios")
 */
class ArchivoController extends Controller{

    public function indexAction(Request $request)
    {
        $servicios = $this->get('Servicios');
        // Recuperar datos del usuario logeado
        $user = $this->getUser();
        $idUser = $user->getId();
        
        $clave = $this->identificarRole($user);
        
        if ($clave == 0) {
            $archivo = $this->getDoctrine()->getRepository('AacBundle:Archivo')
                        ->findBuscarPorUsuario($idUser);           
        }else {
            $archivo = $this->getDoctrine()->getRepository('AacBundle:Archivo')->findAll();
        }

        if (!$archivo) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'message' => 'No existe ningún archivo para este usuario.'
                )
            );            
            return $this->redirect($this->generateUrl('aac/inicio'));
        }
        
        $lista1 = array();
        foreach ($archivo as $value) {
            $lista1[] = $value;
        }
        
        // Crear lista cambiando los nombres 'de' y 'para'
        $lista = $this->crearLista($lista1);
        
        // Añadimos el paginador (En este caso el parámetro "1" es la página actual, 
        // y parámetro "10" es el número de páginas a mostrar)
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
             $lista,
            $this->get('request')->query->get('page', 1),5
        );        
        
        $parametros['entities'] = $lista;
        $parametros['pagination'] = $pagination;
        $parametros['modal'] = $servicios->modalArchivo();
        $parametros['titulo'] = 'Archivos para descargar';
        return $this->render('AacBundle:Archivos:lista_archivos.html.twig', $parametros);        
    }            

    public function subirAction(Request $request)
    {
        $servicios = $this->get('Servicios');
        // Recuperar datos del usuario logeado
        $user = $this->getUser();
        $idUser = $user->getId();

        $clave = $this->identificarRole($user);
        
        $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')->findAll();

        if (!$usuarios) {
            throw $this->createNotFoundException('No hay Usuarios.');
        }
        
        $lista1 = array();
        foreach ($usuarios as $value) {
            $lista1[] = $value;
        }
        $lista = array();
        for($i = 0; $i < count($lista1); $i++){
            if ($i == 0) {
                $lista[$i]['id'] = '99999';
                $lista[0]['nombre'] = 'Para todos';
            } 
            $lista[$i+1]['id'] = $lista1[$i]->getId();
            $lista[$i+1]['nombre'] = $lista1[$i]->getNombre();
        }
        
        if ($clave == 0){
            $lista = array();
            $lista[0]['id'] = '99998';
            $lista[0]['nombre'] = 'Para interventor de Aac';
        }        
        
        $archivo = new Archivo();
        $form = $this->createForm(new ArchivoType(), $archivo);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $archivo->upload();
            $em->persist($archivo);

            $archivo->setFecha(new \DateTime("now"));
            $archivo->setDe($idUser);
            $em->flush();

            return $this->redirect($this->generateUrl('archivos'));
        }
     
        $parametros['form'] = $form->createView();
        $parametros['modal'] = $servicios->modalArchivo();
        $parametros['titulo'] = 'Subir Archivos';
        return $this->render('AacBundle:Archivos:add.html.twig', $parametros);        
    }
    
    public function eliminarAction($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $archivo = $em->getRepository('AacBundle:Archivo')->find($id);

        $nombreArchivo = $archivo->setPath($archivo->getPath())
                ->getAbsolutePath();

        // Borrar archivo del disco
        if (file_exists($nombreArchivo)) {
            unlink($nombreArchivo);            
        }
        
        if (!$archivo) {
            $this->get('session')->getFlashBag()->set(
                'danger',
                array(
                    'title' => 'ERROR! ARCHIVO INEXISTENTE',
                    'message' => 'El Archivo ha eliminar no existe.'
                )
            );            
            return $this->redirect($this->generateUrl('archivos'));
        }
        
        $em->remove($archivo);
        $em->flush();
        
        $this->get('session')->getFlashBag()->set(
            'success',
            array(
                'title' => 'ELIMINADO!',
                'message' => 'El Archivo ha sido eliminado satisfactoriamente.'
            )
        );

        return $this->redirect($this->generateUrl('archivos'));        
    }

    public function identificarRole ($user)
    {
        $roleUser = $user->getRoles();
        $rolesAceptados = array('ROLE_REGISTRADO',
                                'ROLE_INTERVENTOR',
                                'ROLE_ADMIN');
        return array_search($roleUser['0'], $rolesAceptados);
    }

    public function crearLista($lista1)
    {
        $lista = array();
        for($i = 0; $i < count($lista1); $i++){
            if ($lista1[$i]->getDe() == 99999) {
                $lista[$i]['id'] = $lista1[$i]->getId();
                $lista[$i]['descripcion'] = $lista1[$i]->getDescripcion();
                $lista[$i]['de'] = $lista1[$i]->getDe();
                $lista[$i]['para'] = $lista1[$i]->getPara();
                $lista[$i]['path'] = $lista1[$i]->getPath();
                $lista[$i]['fecha'] = $lista1[$i]->getFecha();
                $lista[$i]['nombreDe'] = 'Para todos';                
            }else {
                $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')
                        ->find($lista1[$i]->getDe());
                if (!$usuarios) {
                    //throw $this->createNotFoundException('El Usuario - ' . $id . ' - no existe.');
                }else{
                    $lista[$i]['id'] = $lista1[$i]->getId();
                    $lista[$i]['descripcion'] = $lista1[$i]->getDescripcion();
                    $lista[$i]['de'] = $lista1[$i]->getDe();
                    $lista[$i]['para'] = $lista1[$i]->getPara();
                    $lista[$i]['path'] = $lista1[$i]->getPath();
                    $lista[$i]['fecha'] = $lista1[$i]->getFecha();
                    $lista[$i]['nombreDe'] = $usuarios->getNombre();
                }
            }
        }
        for($i = 0; $i < count($lista1); $i++){
            if ($lista1[$i]->getPara() == 99999) {
                $lista[$i]['nombrePara'] = 'Para todos';                
            }else {
                if ($lista1[$i]->getPara() == 99998) {
                   $lista[$i]['nombrePara'] = 'Para interventor de Aac'; 
                }else{
                    $usuarios = $this->getDoctrine()->getRepository('AacUserBundle:User')
                            ->find($lista1[$i]->getPara());
                    if (!$usuarios) {
                        //throw $this->createNotFoundException('El Usuario - ' . $id . ' - no existe.');
                    }else{
                        $lista[$i]['nombrePara'] = $usuarios->getNombre();
                    }                    
                }
            }
        }
        
        return $lista;
    }
}
