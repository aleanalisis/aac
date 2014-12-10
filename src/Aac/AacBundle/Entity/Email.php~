<?php

namespace Aac\AacBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Email
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Aac\AacBundle\Entity\EmailRepository")
 */
class Email
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="de", type="integer")
     */
    private $de;

    /**
     * @var integer
     *
     * @ORM\Column(name="para", type="integer")
     */
    private $para;

    /**
     * @var string
     *
     * @ORM\Column(name="asunto", type="string", length=255)
     * @Assert\NotNull(message="El asunto no puede estar en blanco.")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "El campo Nombre debe tener un máximo de {{ limit }} caracteres."
     * )
     */
    private $asunto;

    /**
     * @ORM\Column(name="cabecera", type="text")
     * @Assert\NotNull(message="La cabecera no puede estar en blanco.")
     */
    
    private $cabecera;
    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text")
     * @Assert\NotNull(message="El texto del E-mail no puede estar en blanco.")
     */
    private $texto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enviado", type="boolean")
     */
    private $enviado;

    /**
     * @var boolean
     *
     * @ORM\Column(name="masivo", type="boolean")
     */
    private $masivo;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set de
     *
     * @param string $de
     * @return Email
     */
    public function setDe($de)
    {
        $this->de = $de;

        return $this;
    }

    /**
     * Get de
     *
     * @return string 
     */
    public function getDe()
    {
        return $this->de;
    }

    /**
     * Set para
     *
     * @param string $para
     * @return Email
     */
    public function setPara($para)
    {
        $this->para = $para;

        return $this;
    }

    /**
     * Get para
     *
     * @return string 
     */
    public function getPara()
    {
        return $this->para;
    }

    /**
     * Set asunto
     *
     * @param string $asunto
     * @return Email
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * Get asunto
     *
     * @return string 
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set cabecera
     *
     * @param string $cabecera
     * @return Email
     */
    public function setCabecera($cabecera)
    {
        $this->cabecera = $cabecera;

        return $this;
    }

    /**
     * Get cabecera
     *
     * @return string 
     */
    public function getCabecera()
    {
        return $this->cabecera;
    }

    /**
     * Set texto
     *
     * @param string $texto
     * @return Email
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string 
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Email
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set enviado
     *
     * @param boolean $enviado
     * @return Email
     */
    public function setEnviado($enviado)
    {
        $this->enviado = $enviado;

        return $this;
    }

    /**
     * Get enviado
     *
     * @return boolean 
     */
    public function getEnviado()
    {
        return $this->enviado;
    }

    /**
     * Set masivo
     *
     * @param boolean $masivo
     * @return Email
     */    
    public function setMasivo($masivo)
    {
        $this->masivo = $masivo;

        return $this;
    }

    /**
     * Get masivo
     *
     * @return boolean 
     */
    public function getMasivo()
    {
        return $this->masivo;
    }    
}
