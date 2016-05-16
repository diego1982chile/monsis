<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComentarioIncidencia
 *
 * @ORM\Table(name="comentario_incidencia")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\ComentarioIncidenciaRepository")
 */
class ComentarioIncidencia
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
     * @ORM\Column(name="comentario", type="string", length=1023)
     */
    private $comentario;
    
    /**
     * @var \Incidencia
     *
     * @ORM\ManyToOne(targetEntity="Incidencia", inversedBy="comentariosIncidencia")
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
     * Set comentario
     *
     * @param string $comentario
     *
     * @return ComentarioIncidencia
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }
    
    /**
     * Set incidencia
     *
     * @return \Fonasa\MonitorBundle\Entity\ComentarioIncidencia
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
    * @return ComentarioIncidencia
    */
    public function setIdIncidencia($idIncidencia)
    {
        $this->idIncidencia = $idIncidencia;
        
        return $this;
    }    
}

