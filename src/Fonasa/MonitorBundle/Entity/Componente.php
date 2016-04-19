<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Componente
 *
 * @ORM\Table(name="componente")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\ComponenteRepository")
 */
class Componente
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
     * @ORM\OneToMany(targetEntity="Incidencia", mappedBy="componente")          
     */
    protected $incidencias;     
    
    /**          
     * @ORM\OneToMany(targetEntity="Mantencion", mappedBy="componente")          
     */
    protected $mantenciones;     
    
    /**          
     * @ORM\OneToMany(targetEntity="CategoriaIncidencia", mappedBy="componente")          
     */
    protected $categoriasIncidencia;       
    
    /**          
     * @ORM\OneToMany(targetEntity="TipoRequerimiento", mappedBy="componente")          
     */
    protected $tiposRequerimiento; 

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
     *
     * @param string $id
     *
     * @return Componente
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }    

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Componente
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
     * @return Componente
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
     * Get categoriasIncidencia
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoriasIncidencia()
    {
        return $this->categoriasIncidencia;
    }        
    
    /**
     * Get categoriasMantencion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTiposRequerimiento()
    {
        return $this->tiposRequerimiento;
    }            
}

