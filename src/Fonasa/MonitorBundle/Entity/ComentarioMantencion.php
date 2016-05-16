<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComentarioMantencion
 *
 * @ORM\Table(name="comentario_mantencion")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\ComentarioMantencionRepository")
 */
class ComentarioMantencion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="comentario", type="string", length=1023)
     */
    private $comentario;
    
    /**
     * @var \Mantencion
     *
     * @ORM\ManyToOne(targetEntity="Mantencion", inversedBy="comentariosMantencion")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_MANTENCION", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    protected $mantencion;
    
    /**
     *      
     * @ORM\Column(name="ID_MANTENCION", type="integer", nullable=true)               
     */
    private $idMantencion;     

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     *
     * @return ComentarioMantencion
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }
    
     /**
     * Set mantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\ComentarioMantencion
     */
    public function setMantencion(\Fonasa\MonitorBundle\Entity\Mantencion $mantencion = null)
    {
        $this->mantencion=$mantencion;
        
        return $this;
    }    
    
    /**
     * Get idMantencion
     *
     * @return int
     */
    public function getIdMantencion()
    {
        return $this->idMantencion;
    }
    
    /**
    * Set idMantencion
    *
    * @param int $idMantencion
    * @return ComentarioMantencion
    */
    public function setIdMantencion($idMantencion)
    {
        $this->idMantencion = $idMantencion;
        
        return $this;
    }    
}

