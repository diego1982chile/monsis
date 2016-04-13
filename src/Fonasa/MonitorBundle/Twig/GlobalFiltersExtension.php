<?php
namespace Fonasa\MonitorBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Cadem\ReporteBundle\Helper\FiltersHelper;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GlobalFiltersExtension
 *
 * @author diego
 */
class GlobalFiltersExtension extends \Twig_Extension {
    //put your code here
    private $container;
    public function __construct(Container $container=null)
    {
        $this->container = $container;
        //var_dump ($container); exit; //  prints null !!!
    } 
    
     protected function get($service)
    {
        return $this->container->get($service);
    }
        
    public function getGlobals()
    {
        return array(
            "GlobalFilters" => $this->container->get("monsis.helper.filters")->getDefaultFiltersForm()
        );
    }

    public function getName()
    {
        return 'GlobalFilters_extention';
    }
    
     public function getFilters()
     {
        return array(
            new \Twig_Filter_Function('GlobalFilters', array($this, 'GlobalFilters')),
        );
    } 
}
