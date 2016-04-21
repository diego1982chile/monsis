<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Incidencia
 *
 * @ORM\Table(name="incidencia")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\IncidenciaRepository")
 */
class Incidencia
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
     * @var integer
     *
     * @ORM\Column(name="numero_ticket", type="integer")
     */
    private $numeroTicket;
    
    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=1023)
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_reporte", type="datetime")
     */
    private $fechaReporte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_ingreso", type="datetime")
     */
    private $fechaIngreso;
        
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="datetime", nullable=true)
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_salida", type="datetime", nullable=true)
     */
    private $fechaSalida;
    
    /**
     * @var float
     *
     * @ORM\Column(name="hh_efectivas", type="float", nullable=true)
     */
    private $hhEfectivas;    

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_ult_hh", type="datetime", nullable=true)
     */
    private $fechaUltHh;
        
    /**
     * @var \Componente
     *
     * @ORM\ManyToOne(targetEntity="Componente", inversedBy="incidencias")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_COMPONENTE", referencedColumnName="id")
     * })
     */
    protected $componente;
    
    /**
     *      
     * @ORM\Column(name="ID_COMPONENTE", type="integer", nullable=true)               
     */
    protected $idComponente;     
    
    /**
     * @var \Severidad
     *
     * @ORM\ManyToOne(targetEntity="Severidad", inversedBy="incidencias")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_SEVERIDAD", referencedColumnName="id")
     * })
     */
    protected $severidad;
    
    /**
     *      
     * @ORM\Column(name="ID_SEVERIDAD", type="integer", nullable=true)               
     */
    protected $idSeveridad;         
    
    
    /**
     * @var \OrigenIncidencia
     *
     * @ORM\ManyToOne(targetEntity="OrigenIncidencia", inversedBy="incidencias")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_ORIGEN_INCIDENCIA", referencedColumnName="id")
     * })
     */
    protected $origenIncidencia;
    
    /**
     *      
     * @ORM\Column(name="ID_ORIGEN_INCIDENCIA", type="integer", nullable=true)               
     */
    protected $idOrigenIncidencia;                      
    
    /**
     * @var \EstadoIncidencia
     *
     * @ORM\ManyToOne(targetEntity="EstadoIncidencia", inversedBy="incidencias")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_ESTADO_INCIDENCIA", referencedColumnName="id")
     * })
     */
    protected $estadoIncidencia;
    
    /**
     *      
     * @ORM\Column(name="ID_ESTADO_INCIDENCIA", type="integer", nullable=true)               
     */
    protected $idEstadoIncidencia;           
    
    /**
     * @var \CategoriaIncidencia
     *
     * @ORM\ManyToOne(targetEntity="CategoriaIncidencia", inversedBy="incidencias")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_CATEGORIA_INCIDENCIA", referencedColumnName="id")
     * })
     */
    protected $categoriaIncidencia;
    
    /**
     *      
     * @ORM\Column(name="ID_CATEGORIA_INCIDENCIA", type="integer", nullable=true)               
     */
    protected $idCategoriaIncidencia;              


    /**          
     * @ORM\OneToMany(targetEntity="HistorialIncidencia", mappedBy="incidencia")          
     */
    protected $historialesIncidencia;    
    
    /**          
     * @ORM\OneToMany(targetEntity="Mantencion", mappedBy="incidencia")          
     */
    protected $mantenciones;        
    
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
     * Set id
     * @param int $id
     * @return Incidencia
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
        
    }    
    
    /**
     * Set numeroTicket
     *
     * @param integer $numeroTicket
     *
     * @return Incidencia
     */
    public function setNumeroTicket($numeroTicket)
    {
        $this->numeroTicket = $numeroTicket;

        return $this;
    }

    /**
     * Get numeroTicket
     *
     * @return integer
     */
    public function getNumeroTicket()
    {
        return $this->numeroTicket;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Incidencia
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
     * Set fechaReporte
     *
     * @param \DateTime $fechaReporte
     *
     * @return Incidencia
     */
    public function setFechaReporte($fechaReporte)
    {
        $this->fechaReporte = $fechaReporte;

        return $this;
    }

    /**
     * Get fechaReporte
     *
     * @return \DateTime
     */
    public function getFechaReporte()
    {
        return $this->fechaReporte;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return Incidencia
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }
    
    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return Incidencia
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaSalida
     *
     * @param \DateTime $fechaSalida
     *
     * @return Incidencia
     */
    public function setFechaSalida($fechaSalida)
    {
        $this->fechaSalida = $fechaSalida;

        return $this;
    }

    /**
     * Get fechaSalida
     *
     * @return \DateTime
     */
    public function getFechaSalida()
    {
        return $this->fechaSalida;
    }

    /**
     * Set fechaUltHh
     *
     * @param \DateTime $fechaUltHh
     *
     * @return Incidencia
     */
    public function setFechaUltHh($fechaUltHh)
    {
        $this->fechaUltHh = $fechaUltHh;

        return $this;
    }

    /**
     * Get fechaUltHh
     *
     * @return \DateTime
     */
    public function getFechaUltHh()
    {
        return $this->fechaUltHh;
    }
    
    /**
     * Set hhEfectivas
     *
     * @param float $hhEfectivas
     *
     * @return Incidencia
     */
    public function setHhEfectivas($hhEfectivas)
    {
        $this->hhEfectivas = $hhEfectivas;

        return $this;
    }

    /**
     * Get hhEfectivas
     *
     * @return float
     */
    public function getHhEfectivas()
    {
        return $this->hhEfectivas;
    }
        
    /**
     * Get componente
     *
     * @return \Fonasa\MonitorBundle\Entity\Componente
     */
    public function getComponente()
    {
        return $this->componente;
    }
    
    /**
     * Set componente
     *
     * @return \Fonasa\MonitorBundle\Entity\Servicio
     */
    public function setComponente(\Fonasa\MonitorBundle\Entity\Componente $componente = null)
    {        
        $this->componente=$componente;
        
        return $this;
    }
    
    /**
     * Get idComponente
     *
     * @return int
     */
    public function getIdComponente()
    {
        return $this->idComponente;
    }
    
    /**
    * Set idComponente
    *
    * @param int $idComponente
    * @return \Fonasa\MonitorBundle\Entity\Incidencia
    */
    public function setIdComponente($idComponente)
    {
        $this->idComponente = $idComponente;
        
        return $this;
    }     
    
   /**
     * Get origenIncidencia
     *
     * @return \Fonasa\MonitorBundle\Entity\OrigenIncidencia
     */
    public function getOrigenIncidencia()
    {
        return $this->origenIncidencia;
    }
    
    /**
     * Set origenIncidencia
     *
     * @return \Fonasa\MonitorBundle\Entity\Incidencia
     */
    public function setOrigenIncidencia(\Fonasa\MonitorBundle\Entity\OrigenIncidencia $origenIncidencia = null)
    {
        $this->origenIncidencia=$origenIncidencia;
        
        return $this;
    }
    
    /**
     * Get idOrigenIncidencia
     *
     * @return int
     */
    public function getIdOrigenIncidencia()
    {
        return $this->idOrigenIncidencia;
    }
    
    /**
    * Set idOrigenIncidencia
    *
    * @param int $idOrigenIncidencia
    * @return Incidencia
    */
    public function setIdOrigenIncidencia($idOrigenIncidencia)
    {
        $this->idOrigenIncidencia = $idOrigenIncidencia;
        
        return $this;
    }          

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
     * @return \Fonasa\MonitorBundle\Entity\Incidencia
     */
    public function setEstadoIncidencia(\Fonasa\MonitorBundle\Entity\EstadoIncidencia $estadoIncidencia = null)
    {
        $this->estadoIncidencia=$estadoIncidencia;
        
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
    * @param int $idEstadoIncidencia
    * @return Incidencia
    */
    public function setIdEstadoIncidencia($idEstadoIncidencia)
    {
        $this->idEstadoIncidencia = $idEstadoIncidencia;
        
        return $this;
    }  

    //-----------------------------------------------------------------            
    
    /**
     * Get categoriaIncidencia
     *
     * @return \Fonasa\MonitorBundle\Entity\CategoriaIncidencia
     */
    public function getCategoriaIncidencia()
    {
        return $this->categoriaIncidencia;
    }
    
    /**
     * Set categoriaIncidencia
     *
     * @return \Fonasa\MonitorBundle\Entity\CategoriaIncidencia
     */
    public function setCategoriaIncidencia(\Fonasa\MonitorBundle\Entity\CategoriaIncidencia $categoriaIncidencia = null)
    {
        $this->categoriaIncidencia=$categoriaIncidencia;
        
        return $this;
    }  
    
    /**
     * Get idCategoriaIncidencia
     *
     * @return int
     */
    public function getIdCategoriaIncidencia()
    {
        return $this->idCategoriaIncidencia;
    }
    
    /**
    * Set idCategoriaIncidencia
    *
    * @param int $idCategoriaIncidencia
    * @return Incidencia
    */
    public function setIdCategoriaIncidencia($idCategoriaIncidencia)
    {
        $this->idCategoriaIncidencia = $idCategoriaIncidencia;
        
        return $this;
    } 
    
    //-------------------------------------------------------------------------
    
    /**
     * Get severidad
     *
     * @return \Fonasa\MonitorBundle\Entity\Severidad
     */
    public function getSeveridad()
    {
        return $this->severidad;
    }
    
    /**
     * Set severidad
     *
     * @return \Fonasa\MonitorBundle\Entity\Incidencia
     */
    public function setSeveridad(\Fonasa\MonitorBundle\Entity\Severidad $severidad = null)
    {
        $this->severidad=$severidad;
        
        return $this;
    }  
    
    /**
     * Get idSeveridad
     *
     * @return int
     */
    public function getIdSeveridad()
    {
        return $this->idSeveridad;
    }
    
    /**
    * Set idSeveridad
    *
    * @param int $idSeveridad
    * @return Incidencia
    */
    public function setIdSeveridad($idSeveridad)
    {
        $this->idSeveridad = $idSeveridad;
        
        return $this;
    } 
      
    /**
    * Get mantenciones
    *    
    * @return \Doctrine\Common\Collections\Collection
    */
    public function getMantenciones(){
        
        return $this->mantenciones;
    }
}

