<?php
// src/Aac/UserBundle/Entity/User.php

namespace Aac\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aac\UserBundle\Entity\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=200, nullable=false)
     * @Assert\NotNull(message="El campo nombre no puede estar en blanco.")
     * @Assert\Length(
     *      min = 5,
     *      max = 200,
     *      minMessage = "El campo Nombre debe tener un mínimo de {{ limit }} caracteres.",
     *      maxMessage = "El campo Nombre debe tener un máximo de {{ limit }} caracteres."
     * )
     */
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=9, nullable=true)
     * @Assert\Length(
     *      min = 9,
     *      max = 9,
     *      minMessage = "El telefono debe tener {{ limit }} digitos exactamente.",
     *      maxMessage = "El telefono debe tener {{ limit }} digitos exactamente."
     * )
     */
    private $telefono;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nivel", type="integer", length=1)
     */
    private $nivel;

    /**
     * 
     */
    private $enviarEmail;
    
    /**
     * @var \DateTime
     */
    protected $credentialsExpireAt;  

    /**
     * @var string
     *
     * @ORM\Column(name="empresa", type="string", length=255, nullable=false)
     * @Assert\NotNull(message="El campo empresa no puede estar en blanco.")
     * @Assert\Length(
     *      min = 5,
     *      max = 200,
     *      minMessage = "El campo Empresa debe tener un mínimo de {{ limit }} caracteres.",
     *      maxMessage = "El campo Empresa debe tener un máximo de {{ limit }} caracteres."
     * )
     */
    private $empresa;
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

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
     * Set nombre
     *
     * @param string $nombre
     * @return User
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    
    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }    

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return User
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set nivel
     *
     * @param integer $nivel
     * @return User
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return integer 
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * @param \DateTime $date
     *
     * @return User
     */
    public function setCredentialsExpireAt(\DateTime $date = null)
    {
        $this->credentialsExpireAt = $date;

        return $this;
    }
    /**
     * Sets enviarEmail.
     *
     * @param boolean $enviarEmail
     */
    public function setEnviarEmail(Boolean $enviarEmail = null)
    {
        $this->enviarEmail = $enviarEmail;
    }

    /**
     * Get enviarEmail.
     *
     * @return boolean
     */
    public function getEnviarEmail()
    {
        return $this->enviarEmail;
    }

    /**
     * Set empresa
     *
     * @param string $empresa
     * @return User
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return string 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }    
}
