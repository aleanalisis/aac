<?php

namespace Aac\AacBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Archivo
 *
 * @ORM\Table(name="archivo")
 * @ORM\Entity
 */
class Archivo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=200, nullable=false)
     * @Assert\NotBlank
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="de", type="integer", nullable=true)
     */
    private $de;

    /**
     * @var integer
     *
     * @ORM\Column(name="para", type="integer", nullable=true)    *
     */
    private $para;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;    

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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Archivo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set de
     *
     * @param integer $de
     * @return Archivo
     */
    public function setDe($de)
    {
        $this->de = $de;

        return $this;
    }

    /**
     * Get de
     *
     * @return integer 
     */
    public function getDe()
    {
        return $this->de;
    }

    /**
     * Set para
     *
     * @param integer $para
     * @return Archivo
     */
    public function setPara($para)
    {
        $this->para = $para;

        return $this;
    }

    /**
     * Get para
     *
     * @return integer
     */
    public function getPara()
    {
        return $this->para;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Archivo
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
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Archivo
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }
    
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // se deshace del __DIR__ para no meter la pata
        // al mostrar el documento/imagen cargada en la vista.
        return 'bundles/aac/archivos';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // aquí usa el nombre de archivo original pero lo debes
        // sanear al menos para evitar cualquier problema de seguridad
        $caracteres = array('!', '@', '#', '$', '%', '&', '-', ' ');
        $archivo = str_replace($caracteres, "_", $this->getFile()->getClientOriginalName());
        $caracteres = array('á', 'Á', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú');
        $reemplazar = array('a', 'A', 'e', 'E', 'i', 'I', 'o', 'O', 'u', 'U');
        $archivo = str_replace($caracteres, $reemplazar, $archivo);

        // move takes the target directory and then the
        // target filename to move to
        
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $archivo
        );

        // set the path property to the filename where you've saved the file
        $this->path = $archivo;

        // limpia la propiedad «file» ya que no la necesitas más
        $this->file = null;
    }    
}
