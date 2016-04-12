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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

