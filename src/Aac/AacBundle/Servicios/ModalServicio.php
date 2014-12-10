<?php
// src/Aac/AacBundle/Servicios/ModalServicio.php

namespace Aac\AacBundle\Servicios;

class ModalServicio
{

    public function modalUsuario()
    {
        $modal['message'] = '¿ Realmente desea eliminar este registro ?';
        $modal['href_cancel'] = 'usuarios';
        $modal['href_action'] = 'usuarios_eliminar';
        $modal['param'] = '';
        $modal['text_btn'] = 'Eliminar';
        $modal['url_base'] = '/usuarios';

        return $modal;
    }
	
    public function modalArchivo()
    {
        $modal['message'] = '¿ Realmente desea eliminar este archivo del DISCO ?';
        $modal['href_cancel'] = 'archivos';
        $modal['href_action'] = 'archivos_eliminar';
        $modal['param'] = '';
        $modal['text_btn'] = 'Eliminar';
        $modal['url_base'] = '/archivos';

        return $modal;
    }	
}