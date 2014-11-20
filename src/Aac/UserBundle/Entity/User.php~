<?php
// src/Aac/UserBundle/Entity/User.php

namespace Aac\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
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
}
