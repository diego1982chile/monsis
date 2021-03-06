<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Severidad
 *
 * @ORM\Table(name="severidad")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\SeveridadRepository")
 */
class Severidad
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
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="sla", type="integer", nullable=false)
     */
    private $sla;    

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=511, nullable=true)
     */
    private $descripcion;
    
    /**          
     * @ORM\OneToMany(targetEntity="Incidencia", mappedBy="severidad")          
     */
    protected $incidencias;        
    
    /**          
     * @ORM\OneToMany(targetEntity="Mantencion", mappedBy="severidad")          
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Severidad
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
     * Set sla
     *
     * @param integer $sla
     *
     * @return Severidad
     */
    public function setSla($sla)
    {
        $this->sla = $sla;

        return $this;
    }

    /**
     * Get sla
     *
     * @return integer
     */
    public function getSla()
    {
        return $this->sla;
    }    

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Severidad
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
}

