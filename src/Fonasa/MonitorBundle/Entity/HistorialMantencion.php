<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistorialMantencion
 *
 * @ORM\Table(name="historial_mantencion")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\HistorialMantencionRepository")
 */
class HistorialMantencion
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
     * @var \DateTime
     *
     * @ORM\Column(name="inicio", type="datetime")
     */
    private $inicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="termino", type="datetime", nullable=true)
     */
    private $termino;
    
    /**
     * @var \Incidencia
     *
     * @ORM\ManyToOne(targetEntity="Mantencion", inversedBy="historialesMantencion")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_MANTENCION", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    protected $mantencion;
    
    /**
     *      
     * @ORM\Column(name="ID_MANTENCION", type="integer", nullable=true)               
     */
    private $idMantencion;      
    
    
    /**
     * @var \EstadoMantencion
     *
     * @ORM\ManyToOne(targetEntity="EstadoMantencion", inversedBy="historialesMantencion")
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

