<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentoIncidencia
 *
 * @ORM\Table(name="documento_incidencia")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\DocumentoIncidenciaRepository")
 */
class DocumentoIncidencia
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_ult_hh", type="datetime", nullable=true)
     */
    private $fechaHora;    
    
    /**
     * @var \Incidencia
     *
     * @ORM\ManyToOne(targetEntity="Incidencia", inversedBy="documentosIncidencia", cascade={"persist", "remove" })
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
     * @return DocumentoIncidencia
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
     * Get incidencia
     *
     * @return \Fonasa\MonitorBundle\Entity\Incidencia
     */
    public function getIncidencia()
    {
        return $this->incidencia;
    }
    
    /**
     * Set incidencia
     *
     * @return \Fonasa\MonitorBundle\Entity\DocumentoIncidencia
     */
    public function setIncidencia(\Fonasa\MonitorBundle\Entity\Incidencia $incidencia = null)
    {
        $this->incidencia=$incidencia;
        
        return $this;
    }    
    
    /**
     * Get idIncidencia
     *
     * @return int
     */
    public function getIdIncidencia()
    {
        return $this->idIncidencia;
    }
    
    /**
    * Set idIncidencia
    *
    * @param int $idIncidencia
    * @return DocumentoIncidencia
    */
    public function setIdIncidencia($idIncidencia)
    {
        $this->idIncidencia = $idIncidencia;
        
        return $this;
    }
    /*
    public function addIncidencia(Incidencia $incidencia)
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
        }
    }    
    */
}

