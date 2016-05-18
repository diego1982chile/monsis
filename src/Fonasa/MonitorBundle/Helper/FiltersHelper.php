<?php
namespace Fonasa\MonitorBundle\Helper;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Session\Session;

use Fonasa\MonitorBundle\Entity\Componente;

use Doctrine\ORM\EntityManager;
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
    protected $em;
    protected $session;

    public function __construct(FormFactoryInterface $formFactory, EntityManager $em, Session $session)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->session = $session;
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
                    'Resueltas' => 2,
                    //'Todas' => -1
        );  
        
        $componentes = $this->em->getRepository('MonitorBundle:Componente')
        ->createQueryBuilder('c')                                        
        ->orderBy('c.nombre')
        ->getQuery()
        ->getResult(); 
        
        foreach($componentes as $componente){
            $choices_componente[$componente->getNombre()]= $componente->getId();
        }
        
        $choices_componente['Todos']= -1;
          
        /*
        $componente = new Componente();
        $componente->setId(4);
        $componente->setNombre('Todos');
        
        array_push($componentes,$componente);
        */
        $form_visible = 'display:none';
        $label_visible = false;
        
        if($this->session->get('filtroEstado') == 2){
            $form_visible = 'display:inline';
            $label_visible = null;
        }            
        
        return $form_filtros = $this->formFactory->createBuilder()
            ->add('Componente', ChoiceType::class, array(
                  //'class' => 'MonitorBundle:Componente',                    
                  'choices' => $choices_componente,
                  //'choice_label' => 'nombre',                   
                  'choice_attr' => function($val, $key, $index) {
                             // adds a class like attending_yes, attending_no, etc                             
                             //Obtener filtros desde la sesión                             
                             if($this->session->get('filtroComponente') != null){                                  
                                  //echo $this->session->get('filtroComponente').' ';
                                  //echo $val.'\n';
                                  //echo $key;
                                  if($val==$this->session->get('filtroComponente'))                                      
                                      return ['selected' => true];
                                  else
                                      return ['selected' => false];
                             }                             
                             else{
                                if($key=='SIGGES')
                                   return ['selected' => true];
                                else
                                   return ['selected' => false];
                             }                                                          
                   }                                                                                       
            ))
            ->add('Estado', ChoiceType::class, array(
                'choices' => $estados, 
                'expanded' => false, 
                //'data' => 1,                                 
                'choice_attr' => function($val, $key, $index) {
                                    if($this->session->get('filtroEstado') != null){
                                        if($index==$this->session->get('filtroEstado'))
                                            return ['selected' => true];
                                        else
                                            return ['selected' => false];
                                    }
                                    else{
                                        if($index==1)
                                            return ['selected' => true];
                                        else
                                            return ['selected' => false];                                        
                                    }                            
                            },                 
                'attr' => array('class' => 'btn-group','data-toggle' => 'buttons')))               
            ->add('Mes', ChoiceType::class, array(
                  'choices' => $meses, 
                  'choices_as_values' => true, 
                  //'data' => intval(date("m")),
                  'choice_attr' => function($val, $key, $index) {
                                    if($this->session->get('filtroMes') != null){
                                        if($index==$this->session->get('filtroMes'))
                                            return ['selected' => true];
                                        else
                                            return ['selected' => false];
                                    }
                                    else{
                                        if($index==intval(date("m")))
                                            return ['selected' => true];
                                        else
                                            return ['selected' => false];                                        
                                    }                            
                            },                                 
                  'attr' => array('style' => $form_visible),
                  'label' => $label_visible                                                      
                ))
            ->add('Anio', ChoiceType::class, array(
                'choices' => $anyos, 
                //'data' => intval(date("Y"))
                'choice_attr' => function($val, $key, $index) {
                                    if($this->session->get('filtroAnio') != null){
                                        if($index==$this->session->get('filtroAnio'))
                                            return ['selected' => true];
                                        else
                                            return ['selected' => false];
                                    }
                                    else{
                                        if($index==intval(date("Y")))
                                            return ['selected' => true];
                                        else
                                            return ['selected' => false];                                        
                                    }                            
                            },                                 
                'attr' => array('style' => $form_visible),
                'label' => $label_visible                                    
                ))
            ->add('Reset', CheckBoxType::class, array(                  
                'label' => 'Reset Filtros',
                'attr' => array('style' => 'display:none')
            ))                                    
                                  
            ->getForm()->createView();                    
    }
}
