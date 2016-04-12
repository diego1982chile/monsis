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
     * @var string
     *
     * @ORM\Column(name="codigo_origen", type="string", length=255)
     */
    private $codigoOrigen;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_interno", type="string", length=255, unique=true)
     */
    private $codigoInterno;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codigoOrigen
     *
     * @param string $codigoOrigen
     *
     * @return Incidencia
     */
    public function setCodigoOrigen($codigoOrigen)
    {
        $this->codigoOrigen = $codigoOrigen;

        return $this;
    }

    /**
     * Get codigoOrigen
     *
     * @return string
     */
    public function getCodigoOrigen()
    {
        return $this->codigoOrigen;
    }

    /**
     * Set codigoInterno
     *
     * @param string $codigoInterno
     *
     * @return Incidencia
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
}

