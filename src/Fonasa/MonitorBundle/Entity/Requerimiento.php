<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Requerimiento
 *
 * @ORM\Table(name="requerimiento")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\RequerimientoRepository")
 */
class Requerimiento
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
     * @var int
     *
     * @ORM\Column(name="numeroReq", type="integer", unique=true)
     */
    private $numeroReq;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=511)
     */
    private $descripcion;
    
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
     * Set numeroReq
     *
     * @param integer $numeroReq
     *
     * @return Requerimiento
     */
    public function setNumeroReq($numeroReq)
    {
        $this->numeroReq = $numeroReq;

        return $this;
    }

    /**
     * Get numeroReq
     *
     * @return int
     */
    public function getNumeroReq()
    {
        return $this->numeroReq;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Requerimiento
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

