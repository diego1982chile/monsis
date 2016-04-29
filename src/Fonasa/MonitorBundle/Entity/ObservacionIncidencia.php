<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObservacionIncidencia
 *
 * @ORM\Table(name="observacion_incidencia")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\ObservacionIncidenciaRepository")
 */
class ObservacionIncidencia
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
     * @ORM\Column(name="observacion", type="string", length=511)
     */
    private $observacion;
    
    /**
     * @var \Incidencia
     *
     * @ORM\ManyToOne(targetEntity="Incidencia", inversedBy="observacionesIncidencia")
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
     * Set observacion
     *
     * @param string $observacion
     *
     * @return ObservacionIncidencia
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
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
     * @return \Fonasa\MonitorBundle\Entity\ObservacionIncidencia
     */
    public function setIncidencia(\Fonasa\MonitorBundle\Entity\Incidencia $incidencia = null)
    {
        $this->incidencia = $incidencia;
        
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
    * @return ObservacionIncidencia
    */
    public function setIdIncidencia($idIncidencia)
    {
        $this->idIncidencia = $idIncidencia;
        
        return $this;
    }    
}

