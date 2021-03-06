<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoMantencion
 *
 * @ORM\Table(name="tipo_mantencion")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\TipoMantencionRepository")
 */
class TipoMantencion
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
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=511, nullable=true)
     */
    private $descripcion;
    
    /**          
     * @ORM\OneToMany(targetEntity="Mantencion", mappedBy="tipoMantencion")          
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
     * @return TipoMantencion
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
     * Set decripcion
     *
     * @param string $decripcion
     *
     * @return TipoMantencion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get decripcion
     *
     * @return string
     */
    public function getDecripcion()
    {
        return $this->decripcion;
    }
}

