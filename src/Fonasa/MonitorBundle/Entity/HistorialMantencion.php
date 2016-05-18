<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistorialMantencion
 *
 * @ORM\Table(name="historial_mantencion")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\HistorialMantencionRepository")
 */
class HistorialMantencion
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
     * @var \DateTime
     *
     * @ORM\Column(name="inicio", type="datetime")
     */
    private $inicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="termino", type="datetime", nullable=true)
     */
    private $termino;
    
    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="string", length=1023)
     */
    private $observacion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=127)
     */
    private $usuario;
    
    /**
     * @var \Incidencia
     *
     * @ORM\ManyToOne(targetEntity="Mantencion", inversedBy="historialesMantencion")
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
     * @var \EstadoMantencion
     *
     * @ORM\ManyToOne(targetEntity="EstadoMantencion", inversedBy="historialesMantencion")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_ESTADO_MANTENCION", referencedColumnName="id")
     * })
     */
    protected $estadoMantencion;
    
    /**
     *      
     * @ORM\Column(name="ID_ESTADO_MANTENCION", type="integer", nullable=true)               
     */
    private $idEstadoMantencion;      

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
     * Set inicio
     *
     * @param \DateTime $inicio
     *
     * @return HistorialIncidencia
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;

        return $this;
    }

    /**
     * Get inicio
     *
     * @return \DateTime
     */
    public function getInicio()
    {
        return $this->inicio;
    }    
    
    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return HistorialMantencion
     */
    public function setObservacion($observacion)
    {        
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }
    
    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return HistorialMantencion
     */
    public function setUsuario($usuario)
    {        
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }    
    
    /**
     * Get mantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\Mantencion
     */
    public function getMantencion()
    {
        return $this->mantencion;
    }
    
    /**
     * Set mantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\HistorialMantencion
     */
    public function setMantencion(\Fonasa\MonitorBundle\Entity\Mantencion $mantencion = null)
    {
        $this->mantencion = $mantencion;
        
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
    * @return Historial
    */
    public function setIdMantencion($idMantencion)
    {
        $this->idMantencion = $idMantencion;
        
        return $this;
    }      
    
    //-----------------------------------------------------------
    
    /**
     * Get estadoMantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\EstadoMantencion
     */
    public function getEstadoMantencion()
    {
        return $this->estadoMantencion;
    }
    
    /**
     * Set estadoMantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\HistorialMantencion
     */
    public function setEstadoMantencion(\Fonasa\MonitorBundle\Entity\EstadoMantencion $estadoMantencion = null)
    {
        $this->estadoMantencion = $estadoMantencion;
        
        return $this;
    }            
    
    /**
     * Get idEstadoMantencion
     *
     * @return int
     */
    public function getIdEstadoMantencion()
    {
        return $this->idEstadoMantencion;
    }
    
    /**
    * Set idEstadoMantencion
    *
    * @param int $idEstadoMantencion
    * @return HistorialMantencion
    */
    public function setIdEstadoMantencion($idEstadoMantencion)
    {
        $this->idEstadoMantencion = $idEstadoMantencion;
        
        return $this;
    }      
}

