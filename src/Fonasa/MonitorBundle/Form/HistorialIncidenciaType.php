<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Fonasa\MonitorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\FormInterface;

/**
 * Description of HistorialIncidenciaType
 *
 * @author diego
 */
class HistorialIncidenciaType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(            
            'Fecha/Hora Actual' => false,
            'Programado/Retroactivo' => true                    
        ); 
        
        $formModifierInicioProgramado = function (FormInterface $form, $inicioProgramado = null) {                                                               
            
            if($inicioProgramado == null){                                
                return;                   
            }
                
            if($inicioProgramado){
                $form 
                    ->add('inicio', DateTimeType::class, array(
                    'date_widget'=> 'single_text',
                    'date_format'=>'d/MM/y',
                    'data'=> new \DateTime(),
                    'label' => 'Fecha/Hora'
                    //'disabled' => true
                    )); 
            }
            else{
                $form 
                ->remove('inicio');
            }            
        };
        
        $builder->add('observacion', TextType::class, array(
                      'label' => 'Observacion',                                                        
                      'attr' => array('id' => 'observacion','style' => 'margin-top:0px;margin-bottom:10px'),
                ));
                /*
                ->add('inicioProgramado', ChoiceType::class, array(
                      'choices' => $choices, 
                      'label' => false,
                      'choices_as_values' => true, 
                      'expanded' => true,
                      'data' => false,                
                      //'attr' => array('class' => 'btn btn-default','data-toggle' => 'buttons')
                ));                 
                 */
        
        /*
        $builder                        
        ->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifierInicioProgramado) {                    
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();                                        
                $formModifierInicioProgramado($event->getForm(), $data->getInicioProgramado()); 
            }
        );        
        $builder                                    
        ->get('inicioProgramado')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifierInicioProgramado) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $inicioProgramado = $event->getForm()->getData();   
                
                //echo "inicioProgramado=".$inicioProgramado;

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!                    
                $formModifierInicioProgramado($event->getForm()->getParent(), $inicioProgramado);                    
            }
        );    
        */
    }
    
        /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fonasa\MonitorBundle\Entity\HistorialIncidencia',                       
        ));
    }
}
