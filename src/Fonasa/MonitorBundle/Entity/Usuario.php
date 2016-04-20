<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\UsuarioRepository")
 */
class Usuario extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;        
    
    
    /**
     * @var \Area
     *
     * @ORM\ManyToOne(targetEntity="Area", inversedBy="usuarios")
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
     * @ORM\OneToMany(targetEntity="Mantencion", mappedBy="usuario")          
     */
    protected $mantenciones;     
    
    /**          
     * @ORM\OneToMany(targetEntity="HhMantencion", mappedBy="usuario")          
     */
    protected $hhsMantencion;     
    
    public function __construct() {
        parent::__construct();
        // your own logic
    }
        
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
     * Get area
     *
     * @return \Fonasa\MonitorBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->area;
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
     * Set area
     *
     * @return \Fonasa\MonitorBundle\Entity\Usuario
     */
    public function setArea(\Fonasa\MonitorBundle\Entity\Area $area = null)
    {
        $this->area=$area;
        
        return $this;
    }  
    
    /**
    * Set idArea
    *
    * @param int $idArea
    * @return Usuario
    */
    public function setIdArea($idArea)
    {
        $this->idArea = $idArea;
        
        return $this;
    }
}

