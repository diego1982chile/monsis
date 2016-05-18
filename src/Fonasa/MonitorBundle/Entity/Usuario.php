<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\UsuarioRepository")
 */
class Usuario extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;        
    
    
    /**
     * @var \Area
     *
     * @ORM\ManyToOne(targetEntity="Area", inversedBy="usuarios")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_AREA", referencedColumnName="id")
     * })
     */
    protected $area;
    
    /**
     *      
     * @ORM\Column(name="ID_AREA", type="integer", nullable=true)               
     */
    private $idArea;             
        
    /**
     * @var \EstadoMantencion
     *
     * @ORM\ManyToOne(targetEntity="EstadoMantencion", inversedBy="usuarios")
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
     * @var \EstadoIncidencia
     *
     * @ORM\ManyToOne(targetEntity="EstadoIncidencia", inversedBy="usuarios")
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
     * @ORM\OneToMany(targetEntity="Mantencion", mappedBy="usuario")          
     */
    protected $mantenciones; 

    /**          
     * @ORM\OneToMany(targetEntity="Incidencia", mappedBy="usuario")          
     */
    protected $incidencias;     
    
    /**          
     * @ORM\OneToMany(targetEntity="HhMantencion", mappedBy="usuario")          
     */
    protected $hhsMantencion;     
    
    public function __construct() {
        parent::__construct();
        // your own logic
    }
        
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
     * Get area
     *
     * @return \Fonasa\MonitorBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->area;
    }
    
    /**
     * Get idArea
     *
     * @return int
     */
    public function getIdArea()
    {
        return $this->idArea;
    }
    
    /**
     * Set area
     *
     * @return \Fonasa\MonitorBundle\Entity\Usuario
     */
    public function setArea(\Fonasa\MonitorBundle\Entity\Area $area = null)
    {
        $this->area=$area;
        
        return $this;
    }  
    
    /**
    * Set idArea
    *
    * @param int $idArea
    * @return Usuario
    */
    public function setIdArea($idArea)
    {
        $this->idArea = $idArea;
        
        return $this;
    }
    
    //--------------------------------------------------------------------------
        
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
     * Get idEstadoMantencion
     *
     * @return int
     */
    public function getIdEstadoMantencion()
    {
        return $this->idEstadoMantencion;
    }
    
    /**
     * Set estadoMantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\Usuario
     */
    public function setEstadoMantencion(\Fonasa\MonitorBundle\Entity\EstadoMantencion $estadoMantencion = null)
    {
        $this->estadoMantencion=$estadoMantencion;
        
        return $this;
    }  
    
    /**
    * Set idEstadoMantencion
    *
    * @param int $idEstadoMantencion
    * @return Usuario
    */
    public function setIdEstadoMantencion($idEstadoMantencion)
    {
        $this->idEstadoMantencion = $idEstadoMantencion;
        
        return $this;
    }
    
    //--------------------------------------------------------------------------
        
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
     * Get idEstadoIncidencia
     *
     * @return int
     */
    public function getIdEstadoIncidencia()
    {
        return $this->idEstadoIncidencia;
    }
    
    /**
     * Set estadoIncidencia
     *
     * @return \Fonasa\MonitorBundle\Entity\Usuario
     */
    public function setEstadoIncidencia(\Fonasa\MonitorBundle\Entity\EstadoIncidencia $estadoIncidencia = null)
    {
        $this->estadoIncidencia=$estadoIncidencia;
        
        return $this;
    }  
    
    /**
    * Set idEstadoIncidencia
    *
    * @param int $idEstadoIncidencia
    * @return Usuario
    */
    public function setIdEstadoIncidencia($idEstadoIncidencia)
    {
        $this->idEstadoIncidencia = $idEstadoIncidencia;
        
        return $this;
    }    
}

