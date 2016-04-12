<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TareaUsuario
 *
 * @ORM\Table(name="tarea_usuario")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\TareaUsuarioRepository")
 */
class TareaUsuario
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
     * @var \Area
     *
     * @ORM\ManyToOne(targetEntity="Area", inversedBy="tareasArea")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_AREA", referencedColumnName="id")
     * })
     */
    protected $area;    

    /**
     *      
     * @ORM\Column(name="ID_AREA", type="integer", nullable=true)               
     */
    private $idArea;    
    
    /**
     * @var \Tarea
     *
     * @ORM\ManyToOne(targetEntity="Tarea", inversedBy="tareas")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_TAREA", referencedColumnName="id")
     * })
     */
    protected $tarea;    

    /**
     *      
     * @ORM\Column(name="ID_TAREA", type="integer", nullable=true)               
     */
    private $idTarea;       
    
    /**          
     * @ORM\OneToMany(targetEntity="HhMantencion", mappedBy="tareaUsuario")          
     */
    protected $hhsMantencion;                  
    
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
     * Set id
     *
     * @param string $id
     *
     * @return TareaUsuario
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }        
    
    /**
     * Get area
     *
     * @return \Fonasa\MonitorBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->area;
    }
    
    /**
    * Set area
    *
    * @param \Fonasa\MonitorBundle\Entity\Area
    * @return TareaUsuario
    */
    public function setArea($area)
    {
        $this->area = $area;
        
        return $this;
    }        
    
    /**
     * Get idArea
     *
     * @return int
     */
    public function getIdArea()
    {
        return $this->idArea;
    }
    
    /**
    * Set idArea
    *
    * @param int $idArea
    * @return TareaUsuario
    */
    public function setIdArea($idArea)
    {
        $this->idArea = $idArea;
        
        return $this;
    }
    
    /**
     * Get tarea
     *
     * @return \Fonasa\MonitorBundle\Entity\Tarea
     */
    public function getTarea()
    {
        return $this->tarea;
    }
    
    /**
    * Set tarea
    *
    * @param \Fonasa\MonitorBundle\Entity\Tarea
    * @return TareaUsuario
    */
    public function setTarea($tarea)
    {
        $this->tarea = $tarea;
        
        return $this;
    }            
    
    /**
     * Get idTarea
     *
     * @return int
     */
    public function getIdTarea()
    {
        return $this->idTarea;
    }       
    
    /**
    * Set idTarea
    *
    * @param int $idTarea
    * @return TareaUsuario
    */
    public function setIdTarea($idTarea)
    {
        $this->idTarea = $idTarea;
        
        return $this;
    }
}

