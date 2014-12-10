<?php

/**
 * Description of Hash
 * @author ©-2014 Antonio Lorenzo Esparza 25-jul-2014
 */
namespace Aac\AacBundle\Servicios;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Servicios implements PasswordEncoderInterface
{
    protected $container;

    public function __construct($raw, $salt, ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public static function getHash($algoritmo, $data, ContainerInterface $container)
    {
        $hash = hash_init($algoritmo, HASH_HMAC, $this->container->getParameter('secret'));
        hash_update($hash, $data);
        
        return hash_final($hash);
    }
    
    public function encodePassword($raw, $salt)
    {
        $hash = hash_init('sha512', HASH_HMAC, $this->container->getParameter('secret'));
        hash_update($hash, $raw);
        
        //return hash_final($hash);
        return hash_final($hash); // Custom function for password encrypt
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $encoded === $this->encodePassword($raw, $salt);
    }

    public function modalUsuario()
    {
        $modal['message'] = '¿ Realmente desea eliminar este usuario ?';
        $modal['href_cancel'] = 'usuarios';
        $modal['href_action'] = 'usuarios_eliminar';
        $modal['param'] = '';
        $modal['text_btn'] = 'Eliminar';
        $modal['url_base'] = '/usuarios';

        return $modal;
    }
    
    public function modalArchivo()
    {
        $modal['message'] = '¿ Realmente desea eliminar este archivo del Disco ?';
        $modal['href_cancel'] = 'archivos';
        $modal['href_action'] = 'archivos_eliminar';
        $modal['param'] = '';
        $modal['text_btn'] = 'Eliminar';
        $modal['url_base'] = '/archivos';

        return $modal;
    }        

}
