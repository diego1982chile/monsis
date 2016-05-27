<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistorialIncidencia
 *
 * @ORM\Table(name="historial_incidencia")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\HistorialIncidenciaRepository")
 */
class HistorialIncidencia
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
     * @ORM\Column(name="usuario", type="string", length=127, nullable=true)
     */
    private $usuario;
    
    /**
     * @var \Incidencia
     *
     * @ORM\ManyToOne(targetEntity="Incidencia", inversedBy="historialesIncidencia")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_INCIDENCIA", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    protected $incidencia;
    
    /**
     *      
     * @ORM\Column(name="ID_INCIDENCIA", type="integer", nullable=true)               
     */
    private $idIncidencia;      
    
    
    /**
     * @var \EstadoIncidencia
     *
     * @ORM\ManyToOne(targetEntity="EstadoIncidencia", inversedBy="historialesIncidencia")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_ESTADO_INCIDENCIA", referencedColumnName="id")
     * })
     */
    protected $estadoIncidencia;
    
    /**
     *      
     * @ORM\Column(name="ID_ESTADO_INCIDENCIA", type="integer", nullable=true)               
     */
    private $idEstadoIncidencia;     

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
     * Set termino
     *
     * @param \DateTime $termino
     *
     * @return Historial
     */
    public function setTermino($termino)
    {
        $this->termino = $termino;

        return $this;
    }

    /**
     * Get termino
     *
     * @return \DateTime
     */
    public function getTermino()
    {
        return $this->termino;
    }
    
    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return HistorialIncidencia
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
     * @return HistorialIncidencia
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
     * Get incidencia
     *
     * @return \Fonasa\MonitorBundle\Entity\Incidencia
     */
    public function getIncidencia()
    {
        return $this->incidencia;
    }
    
    /**
     * Set incidencia
     *
     * @return \Fonasa\MonitorBundle\Entity\HistorialIncidencia     
     */
    public function setIncidencia(\Fonasa\MonitorBundle\Entity\Incidencia $incidencia = null)
    {
        $this->incidencia = $incidencia;
        
        return $this;
    }            
    
    /**
     * Get idIncidencia
     *
     * @return int
     */
    public function getIdIncidencia()
    {
        return $this->idIncidencia;
    }
    
    /**
    * Set idIncidencia
    *
    * @param int $idServicio
    * @return Historial
    */
    public function setIdIncidencia($idIncidencia)
    {
        $this->idIncidencia = $idIncidencia;
        
        return $this;
    }      
    
    //-----------------------------------------------------------
    
    /**
     * Get estadoIncidencia
     *
     * @return \Fonasa\MonitorBundle\Entity\EstadoIncidencia
     */
    public function getEstadoIncidencia()
    {
        return $this->estadoIncidencia;
    }
    
    /**
     * Set estadoIncidencia
     *
     * @return \Fonasa\MonitorBundle\Entity\HistorialIncidencia
     */
    public function setEstadoIncidencia(\Fonasa\MonitorBundle\Entity\EstadoIncidencia $estadoIncidencia = null)
    {
        $this->estadoIncidencia = $estadoIncidencia;
        
        return $this;
    }            
    
    /**
     * Get idEstadoIncidencia
     *
     * @return int
     */
    public function getIdEstadoIncidencia()
    {
        return $this->idEstadoIncidencia;
    }
    
    /**
    * Set idEstadoIncidencia
    *
    * @param int $idEstado
    * @return HistorialIncidencia
    */
    public function setIdEstadoIncidencia($idEstadoIncidencia)
    {
        $this->idEstadoIncidencia = $idEstadoIncidencia;
        
        return $this;
    }
        
}

