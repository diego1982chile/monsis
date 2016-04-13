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
     * @var \CategoriaMantencion
     *
     * @ORM\ManyToOne(targetEntity="CategoriaMantencion", inversedBy="mantenciones")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_CATEGORIA_MANTENCION", referencedColumnName="id")
     * })
     */
    protected $categoriaMantencion;
    
    /**
     *      
     * @ORM\Column(name="ID_CATEGORIA_MANTENCION", type="integer", nullable=true)               
     */
    protected $idCategoriaMantencion;              


    /**          
     * @ORM\OneToMany(targetEntity="HistorialMantencion", mappedBy="mantencion")          
     */
    protected $historialesMantencion;    
    
    
    /**          
     * @ORM\OneToMany(targetEntity="HhMantencion", mappedBy="mantencion")          
     */
    protected $hhsMantencion;        
    

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
     * @return Mantencion
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
}

