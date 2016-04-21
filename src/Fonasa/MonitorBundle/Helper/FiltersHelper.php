<?php
namespace Fonasa\MonitorBundle\Helper;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FiltersHelper
 *
 * @author diego
 */
class FiltersHelper {
    //put your code here
    protected $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }
        
    public function getDefaultFiltersForm(){
        $anyo = date("Y");
        $anyos = array();
        for($i=0;$i<10;++$i)
            $anyos[$anyo-$i] = $anyo-$i;        
        
        $meses = array(
                    'Enero' => 1,
                    'Febrero' => 2,
                    'Marzo' => 3,
                    'Abril' => 4,
                    'Mayo' => 5,
                    'Junio' => 6,
                    'Julio' => 7,
                    'Agosto' => 8,
                    'Septiembre' => 9,
                    'Octubre' => 10,
                    'Noviembre' => 11,
                    'Diciembre' => 12);
        
        $estados = array(            
                    'Activas' => 1,
                    'Resueltas' => 2                    
        );                
        
        return $form_filtros = $this->formFactory->createBuilder()
            ->add('Anio', ChoiceType::class, array('choices' => $anyos, 'data' => intval(date("Y"))))
            ->add('Mes', ChoiceType::class, array('choices' => $meses, 'choices_as_values' => true, 'data' => intval(date("m"))))
            ->add('Estado', ChoiceType::class, array('choices' => $estados, 'expanded' => true, 'data' => 1, 'attr' => array('class' => 'btn-group','data-toggle' => 'buttons')))
            ->getForm()->createView();                    
    }
}
