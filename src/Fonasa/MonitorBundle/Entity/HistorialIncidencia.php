<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistorialIncidencia
 *
 * @ORM\Table(name="historial_incidencia")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\HistorialIncidenciaRepository")
 */
class HistorialIncidencia
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
     * @ORM\ManyToOne(targetEntity="Incidencia", inversedBy="historialesIncidencia")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_INCIDENCIA", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    protected $incidencia;
    
    /**
     *      
     * @ORM\Column(name="ID_INCIDENCIA", type="integer", nullable=true)               
     */
    private $idIncidencia;      
    
    
    /**
     * @var \EstadoIncidencia
     *
     * @ORM\ManyToOne(targetEntity="EstadoIncidencia", inversedBy="historialesIncidencia")
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

