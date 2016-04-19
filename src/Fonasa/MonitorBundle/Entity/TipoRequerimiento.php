<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoRequerimiento
 *
 * @ORM\Table(name="tipo_requerimiento")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\TipoRequerimientoRepository")
 */
class TipoRequerimiento
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=511, nullable=true)
     */
    private $descripcion;
        
    /**          
     * @ORM\OneToMany(targetEntity="Mantencion", mappedBy="tipoRequerimiento")          
     */
    protected $mantenciones; 
    
    /**
     * @var \Componente
     *
     * @ORM\ManyToOne(targetEntity="Componente", inversedBy="tiposRequerimiento")
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CategoriaMantencion
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return CategoriaMantencion
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
    * @param \Fonasa\MonitorBundle\Entity\Componente
    * @return CategoriaMantencion
    */
    public function setComponente($componente)
    {
        $this->componente = $componente;
        
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
    * @return CategoriaMantencion
    */
    public function setIdComponente($idComponente)
    {
        $this->idComponente = $idComponente;
        
        return $this;
    }     
}

