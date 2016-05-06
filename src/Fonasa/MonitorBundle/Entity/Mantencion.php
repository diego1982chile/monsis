<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mantencion
 *
 * @ORM\Table(name="mantencion")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\MantencionRepository")
 */
class Mantencion
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
     * @ORM\Column(name="numero_requerimiento", type="integer", nullable=true)
     */
    private $numeroRequerimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_interno", type="string", length=255, unique=true)
     */
    private $codigoInterno;    

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=2023, nullable=true)
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_ingreso", type="datetime")
     */
    private $fechaIngreso;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="inicio_programado", type="boolean")
     */
    private $inicioProgramado;    

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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_ult_hh", type="datetime", nullable=true)
     */
    private $fechaUltHh;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tocada", type="datetime", nullable=true)
     */
    private $tocada;
    
    /**
     * @var float
     *
     * @ORM\Column(name="hh_estimadas", type="float", nullable=true)
     */
    private $hhEstimadas;

    /**
     * @var float
     *
     * @ORM\Column(name="hh_efectivas", type="float", nullable=true)
     */
    private $hhEfectivas;

    /**
     * @var \Componente
     *
     * @ORM\ManyToOne(targetEntity="Componente", inversedBy="mantenciones")
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
     * @ORM\ManyToOne(targetEntity="Severidad", inversedBy="mantenciones")
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
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="mantenciones")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_USUARIO", referencedColumnName="id")
     * })
     */
    protected $usuario;
    
    /**
     *      
     * @ORM\Column(name="ID_USUARIO", type="integer", nullable=true)               
     */
    protected $idUsuario;     
    
    
    /**
     * @var \TipoMantencion
     *
     * @ORM\ManyToOne(targetEntity="TipoMantencion", inversedBy="mantenciones")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_TIPO_MANTENCION", referencedColumnName="id")
     * })
     */
    protected $tipoMantencion;
    
    /**
     *      
     * @ORM\Column(name="ID_TIPO_MANTENCION", type="integer", nullable=true)               
     */
    protected $idTipoMantencion;                      
    
    /**
     * @var \EstadoMantencion
     *
     * @ORM\ManyToOne(targetEntity="EstadoMantencion", inversedBy="mantenciones")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_ESTADO_MANTENCION", referencedColumnName="id")
     * })
     */
    protected $estadoMantencion;
    
    /**
     *      
     * @ORM\Column(name="ID_ESTADO_MANTENCION", type="integer", nullable=true)               
     */
    protected $idEstadoMantencion;           
    
    /**
     * @var \TipoRequerimiento
     *
     * @ORM\ManyToOne(targetEntity="TipoRequerimiento", inversedBy="mantenciones")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_TIPO_REQUERIMIENTO", referencedColumnName="id")
     * })
     */
    protected $tipoRequerimiento;
    
    /**
     *      
     * @ORM\Column(name="ID_TIPO_REQUERIMIENTO", type="integer", nullable=true)               
     */
    protected $idTipoRequerimiento;              


    /**          
     * @ORM\OneToMany(targetEntity="HistorialMantencion", mappedBy="mantencion")          
     */
    protected $historialesMantencion;    
    
    
    /**          
     * @ORM\OneToMany(targetEntity="HhMantencion", mappedBy="mantencion")          
     */
    protected $hhsMantencion;        
        
    /**
     * @var \Incidencia
     *
     * @ORM\ManyToOne(targetEntity="Incidencia", inversedBy="mantenciones")
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
     * @var \OrigenMantencion
     *
     * @ORM\ManyToOne(targetEntity="OrigenMantencion", inversedBy="mantenciones")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_ORIGEN_MANTENCION", referencedColumnName="id")
     * })
     */
    protected $origenMantencion;
    
    /**
     *      
     * @ORM\Column(name="ID_ORIGEN_MANTENCION", type="integer", nullable=true)               
     */
    protected $idOrigenMantencion;      
            

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
     * @return Mantencion
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
        
    }    
    
    /**
     * Set numeroRequerimiento
     *
     * @param integer $numeroRequerimiento
     *
     * @return Mantencion
     */
    public function setNumeroRequerimiento($numeroRequerimiento)
    {
        $this->numeroRequerimiento = $numeroRequerimiento;

        return $this;
    }

    /**
     * Get numeroRequerimiento
     *
     * @return integer
     */
    public function getNumeroRequerimiento()
    {
        return $this->numeroRequerimiento;
    }    

    /**
     * Set codigoInterno
     *
     * @param string $codigoInterno
     *
     * @return Mantencion
     */
    public function setCodigoInterno($codigoInterno)
    {
        $this->codigoInterno = $codigoInterno;

        return $this;
    }

    /**
     * Get codigoInterno
     *
     * @return string
     */
    public function getCodigoInterno()
    {
        return $this->codigoInterno;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Mantencion
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
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return Mantencion
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
     * Set inicioProgramado
     *
     * @param boolean $inicioProgramado
     *
     * @return Mantencion
     */
    public function setInicioProgramado($inicioProgramado)
    {
        $this->inicioProgramado = $inicioProgramado;

        return $this;
    }

    /**
     * Get inicioProgramado
     *
     * @return boolean
     */
    public function getInicioProgramado()
    {
        return $this->inicioProgramado;
    }
    

    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return Mantencion
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
     * @return Mantencion
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
     * @return Mantencion
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
     * Set tocada
     *
     * @param \DateTime $tocada
     *
     * @return Incidencia
     */
    public function setTocada($tocada)
    {
        $this->tocada = $tocada;

        return $this;
    }

    /**
     * Get tocada
     *
     * @return \DateTime
     */
    public function getTocada()
    {
        return $this->tocada;
    }    
    
    /**
     * Set hhEstimadas
     *
     * @param integer $hhEstimadas
     *
     * @return Mantencion
     */
    public function setHhEstimadas($hhEstimadas)
    {
        $this->hhEstimadas = $hhEstimadas;

        return $this;
    }

    /**
     * Get hhEstimadas
     *
     * @return int
     */
    public function getHhEstimadas()
    {
        return $this->hhEstimadas;
    }

    /**
     * Set hhEfectivas
     *
     * @param integer $hhEfectivas
     *
     * @return Mantencion
     */
    public function setHhEfectivas($hhEfectivas)
    {
        $this->hhEfectivas = $hhEfectivas;

        return $this;
    }

    /**
     * Get hhEfectivas
     *
     * @return int
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
     * @return \Fonasa\MonitorBundle\Entity\Mantencion
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
    * @return \Fonasa\MonitorBundle\Entity\Mantencion
    */
    public function setIdComponente($idComponente)
    {
        $this->idComponente = $idComponente;
        
        return $this;
    } 
    
    //-------------------------------------------------------------------------
    
    /**
     * Get tipoMantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\TipoMantencion
     */
    public function getTipoMantencion()
    {
        return $this->tipoMantencion;
    }
    
    /**
     * Set tipoMantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\Mantencion
     */
    public function setTipoMantencion(\Fonasa\MonitorBundle\Entity\TipoMantencion $tipoMantencion = null)
    {        
        $this->tipoMantencion=$tipoMantencion;
        
        return $this;
    }
    
    /**
     * Get idTipoMantencion
     *
     * @return int
     */
    public function getIdTipoMantencion()
    {
        return $this->idTipoMantencion;
    }
    
    /**
    * Set idTipoMantencion
    *
    * @param int $idTipoMantencion
    * @return \Fonasa\MonitorBundle\Entity\TipoMantencion
    */
    public function setIdTipoMantencion($idTipoMantencion)
    {
        $this->idTipoMantencion = $idTipoMantencion;
        
        return $this;
    } 
    
    //-------------------------------------------------------------
    
    /**
     * Get tipoRequerimiento
     *
     * @return \Fonasa\MonitorBundle\Entity\TipoRequerimiento
     */
    public function getTipoRequerimiento()
    {
        return $this->tipoRequerimiento;
    }
    
    /**
     * Set tipoRequerimiento
     *
     * @return \Fonasa\MonitorBundle\Entity\Requerimiento
     */
    public function setTipoRequerimiento(\Fonasa\MonitorBundle\Entity\TipoRequerimiento $tipoRequerimiento = null)
    {        
        $this->tipoRequerimiento=$tipoRequerimiento;
        
        return $this;
    }
    
    /**
     * Get idTipoRequerimiento
     *
     * @return int
     */
    public function getIdTipoRequerimiento()
    {
        return $this->idTipoRequerimiento;
    }
    
    /**
    * Set idTipoRequerimiento
    *
    * @param int $idTipoRequerimiento
    * @return \Fonasa\MonitorBundle\Entity\Mantencion
    */
    public function setIdTipoRequerimiento($idTipoRequerimiento)
    {
        $this->idTipoRequerimiento = $idTipoRequerimiento;
        
        return $this;
    } 
    
    //-------------------------------------------------------------
    
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
     * @return \Fonasa\MonitorBundle\Entity\Mantencion
     */
    public function setEstadoMantencion(\Fonasa\MonitorBundle\Entity\EstadoMantencion $estadoMantencion = null)
    {
        $this->estadoMantencion=$estadoMantencion;
        
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
    * @return Mantencion
    */
    public function setIdEstadoMantencion($idEstadoMantencion)
    {
        $this->idEstadoMantencion = $idEstadoMantencion;
        
        return $this;
    }
    
    //-------------------------------------------------------------------------
    
    
    /**
     * Get origenMantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\OrigenMantencion
     */
    public function getOrigenMantencion()
    {
        return $this->origenMantencion;
    }
    
    /**
     * Set origenMantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\Mantencion
     */
    public function setOrigenMantencion(\Fonasa\MonitorBundle\Entity\OrigenMantencion $origenMantencion = null)
    {
        $this->origenMantencion=$origenMantencion;
        
        return $this;
    }    
    
    /**
     * Get idOrigenMantencion
     *
     * @return int
     */
    public function getIdOrigenMantencion()
    {
        return $this->idOrigenMantencion;
    }
    
    /**
    * Set idOrigenMantencion
    *
    * @param int $idOrigenMantencion
    * @return Mantencion
    */
    public function setIdOrigenMantencion($idOrigenMantencion)
    {
        $this->idOrigenMantencion = $idOrigenMantencion;
        
        return $this;
    }
    
    //-------------------------------------------------------------------------    
    
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
     * @return \Fonasa\MonitorBundle\Entity\Incidencia
     */
    public function setIncidencia(\Fonasa\MonitorBundle\Entity\Incidencia $incidencia = null)
    {
        $this->incidencia=$incidencia;
        
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
    * @param int $idIncidencia
    * @return Mantencion
    */
    public function setIdIncidencia($idIncidencia)
    {
        $this->idIncidencia = $idIncidencia;
        
        return $this;
    }
    
    //------------------------------------------------------------------------
    
    /**
     * Get usuario
     *
     * @return \Fonasa\MonitorBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
    
    /**
     * Set usuario
     *
     * @return \Fonasa\MonitorBundle\Entity\Usuario
     */
    public function setUsuario(\Fonasa\MonitorBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario=$usuario;
        
        return $this;
    }    
    
    /**
     * Get idUsuario
     *
     * @return int
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }
    
    /**
    * Set idUsuario
    *
    * @param int $idUsuario
    * @return Usuario
    */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
        
        return $this;
    }
    
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
     * @return \Fonasa\MonitorBundle\Entity\Mantencion
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
    * @return Mantencion
    */
    public function setIdSeveridad($idSeveridad)
    {
        $this->idSeveridad = $idSeveridad;
        
        return $this;
    }
   
}

