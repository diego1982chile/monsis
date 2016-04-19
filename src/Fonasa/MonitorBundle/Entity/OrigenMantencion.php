<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrigenMantencion
 *
 * @ORM\Table(name="origen_mantencion")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\OrigenMantencionRepository")
 */
class OrigenMantencion
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
     * @ORM\OneToMany(targetEntity="Mantencion", mappedBy="origenMantencion")          
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
     * @return OrigenMantencion
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
}

