<?php

namespace Fonasa\MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentoMantencion
 *
 * @ORM\Table(name="documento_mantencion")
 * @ORM\Entity(repositoryClass="Fonasa\MonitorBundle\Repository\DocumentoMantencionRepository")
 */
class DocumentoMantencion
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
     * @var string
     *
     * @ORM\Column(name="archivo", type="string", length=255, unique=true)
     */
    private $archivo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaHora", type="datetime", nullable=true)
     */
    private $fechaHora;
    
    /**
     * @var \Mantencion
     *
     * @ORM\ManyToOne(targetEntity="Mantencion", inversedBy="documentosMantencion")
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
     * @var \TipoDocumentoMantencion
     *
     * @ORM\ManyToOne(targetEntity="TipoDocumentoMantencion", inversedBy="documentosMantencion")
     * @ORM\JoinColumns{(
     *    @ORM\JoinColumn(name="ID_TIPO_DOCUMENTO_MANTENCION", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    protected $tipoDocumentoMantencion;
    
    /**
     *      
     * @ORM\Column(name="ID_TIPO_DOCUMENTO_MANTENCION", type="integer", nullable=true)               
     */
    private $idTipoDocumentoMantencion;    


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
     * @return DocumentoMantencion
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
     * Set archivo
     *
     * @param string $archivo
     *
     * @return DocumentoMantencion
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;

        return $this;
    }

    /**
     * Get archivo
     *
     * @return string
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set fechaHora
     *
     * @param \DateTime $fechaHora
     *
     * @return DocumentoMantencion
     */
    public function setFechaHora($fechaHora)
    {
        $this->fechaHora = $fechaHora;

        return $this;
    }

    /**
     * Get fechaHora
     *
     * @return \DateTime
     */
    public function getFechaHora()
    {
        return $this->fechaHora;
    }
    
    /**
     * Get mantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\Mantencion
     */
    public function getMantencion()
    {
        return $this->mantencion;
    }
    
    /**
     * Set mantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\DocumentoMantencion
     */
    public function setMantencion(\Fonasa\MonitorBundle\Entity\Mantencion $mantencion = null)
    {
        $this->mantencion = $mantencion;
        
        return $this;
    }    
    
    /**
     * Get idMantencion
     *
     * @return int
     */
    public function getIdMantencion()
    {
        return $this->idMantencion;
    }
    
    /**
    * Set idMantencion
    *
    * @param int $idMantencion
    * @return DocumentoMantencion
    */
    public function setIdMantencion($idMantencion)
    {
        $this->idMantencion = $idMantencion;
        
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
    
    /**
     * Get tipoDocumentoMantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\TipoDocumentoMantencion
     */
    public function getTipoDocumentoMantencion()
    {
        return $this->tipoDocumentoMantencion;
    }
    
    /**
     * Set tipoDocumentoMantencion
     *
     * @return \Fonasa\MonitorBundle\Entity\DocumentoMantencion
     */
    public function setTipoDocumentoMantencion(\Fonasa\MonitorBundle\Entity\TipoDocumentoMantencion $tipoDocumentoMantencion = null)
    {
        $this->tipoDocumentoMantencion=$tipoDocumentoMantencion;
        
        return $this;
    }    
    
    /**
     * Get idTipoDocumentoMantencion
     *
     * @return int
     */
    public function getIdTipoDocumentoMantencion()
    {
        return $this->idTipoDocumentoMantencion;
    }
    
    /**
    * Set idTipoDocumentoMantencion
    *
    * @param int $idTipoDocumentoMantencion
    * @return DocumentoMantencion
    */
    public function setIdTipoDocumentoMantencion($idTipoDocumentoMantencion)
    {
        $this->idTipoDocumentoMantencion = $idTipoDocumentoMantencion;
        
        return $this;
    }            
}

