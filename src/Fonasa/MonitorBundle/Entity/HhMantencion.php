<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HhMantencion
 *
 * @ORM\Table(name="hh_mantencion")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\HhMantencionRepository")
 */
class HhMantencion
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
     * @ORM\Column(name="dia", type="date")
     */
    private $dia;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora", type="time")
     */
    private $hora;

    /**
     * @var \Mantencion
     *
     * @ORM\ManyToOne(targetEntity="Mantencion", inversedBy="hhsMantencion")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_MANTENCION", referencedColumnName="id")
     * })
     */
    protected $mantencion;
    
    /**
     *      
     * @ORM\Column(name="ID_MANTENCION", type="integer", nullable=true)               
     */
    private $idMantencion;  
    
    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="hhsMantencion")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_USUARIO", referencedColumnName="id")
     * })
     */
    protected $usuario;
    
    /**
     *      
     * @ORM\Column(name="ID_USUARIO", type="integer", nullable=true)               
     */
    private $idUsuario; 
    
    
    /**
     * @var \TareaUsuario
     *
     * @ORM\ManyToOne(targetEntity="TareaUsuario", inversedBy="hhsMantencion")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_TAREA_USUARIO", referencedColumnName="id")
     * })
     */
    protected $tareaUsuario;
    
    /**
     *      
     * @ORM\Column(name="ID_TAREA_USUARIO", type="integer", nullable=true)               
     */
    private $idTareaUsuario;        
    

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

