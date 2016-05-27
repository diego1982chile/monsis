<?php

namespace Fonasa\MonitorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\FormInterface;

use Doctrine\ORM\EntityRepository;

use Fonasa\MonitorBundle\Entity\OrigenMantencion;
use Fonasa\MonitorBundle\Entity\Componente;
use Fonasa\MonitorBundle\Entity\Severidad;

class MantencionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $filtroComponente = $options['filtroComponente'];                
        
        $formModifierTipoIngreso = function (FormInterface $form, $tipoIngreso = null) {
        
            if($tipoIngreso == null)
                return;        
            
            switch($tipoIngreso){
                case 1:
                    $form 
                        ->remove('fechaInicio')
                        ->remove('estadoMantencion');
                    break;
                case 2:
                     $form 
                        ->add('fechaInicio', DateTimeType::class, array(
                        'date_widget'=> 'single_text',
                        'date_format'=>'d/MM/y',
                        'data'=> new \DateTime(),
                        'attr' => array('style' => 'margin-bottom:14px'),
                        //'disabled' => true
                        ))
                        ->add('estadoMantencion', EntityType::class, array(
                            'class' => 'MonitorBundle:EstadoMantencion',
                            'query_builder' => function (EntityRepository $er) {
                              return $er->createQueryBuilder('ei')
                                        ->orderBy('ei.nombre', 'ASC');
                          },
                          'choice_label' => 'nombre',
                          'placeholder' => 'Seleccione una opción...', 
                          //'expanded' => true,
                          //'multiple' => false,
                          //'position' => 'first',
                          //'attr' => array('class' => 'form-inline')
                        )); 
                    break;
                case 3:
                    $form 
                        ->add('fechaInicio', DateTimeType::class, array(
                        'date_widget'=> 'single_text',
                        'date_format'=>'d/MM/y',
                        'data'=> new \DateTime()
                        //'disabled' => true
                        )); 
                    break;
            }
            
            if($tipoIngreso){               
            }
            else{
                $form 
                ->remove('fechaInicio');
            }            
        };   
        
        $builder        
            ->add('id', HiddenType::class);            
        
        if($options['idIncidencia'] != null){
            $builder        
                ->add('origenMantencion', EntityType::class, array(
                          'class' => 'MonitorBundle:OrigenMantencion',
                          'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('om')
                                      ->orderBy('om.nombre', 'DESC');
                        },
                        'choice_label' => 'nombre',                    
                        'choice_attr' => function($val, $key, $index) {
                             // adds a class like attending_yes, attending_no, etc
                             if($val->getNombre()=='Incidencia')
                                return ['selected' => true];
                             else
                                return ['selected' => false];
                         },                                                               
                         'attr' => array('style' => 'display:none'),
                         'label' => false                                 
                ));
                         
                if($filtroComponente == -1){
                    
                    $builder
                    ->add('componente', EntityType::class, array(
                          'class' => 'MonitorBundle:Componente',
                          'query_builder' => function (EntityRepository $er) use ($options) {
                                           return $er->createQueryBuilder('c')
                                           ->join('c.incidencias', 'i')
                                           ->where('i.id = ?1')
                                           ->setParameter(1, $options['idIncidencia'])                                       
                                           ->orderBy('c.nombre', 'DESC');
                          },                    
                          'choice_label' => 'nombre',                                         
                          'position' => 'first',                      
                          'attr' => array('style' => 'display:none'),
                          'label' => false                    
                          //'position' => array('after' => 'origenMantencion'),
                          //'disabled' => true, 
                    ));   
                                                                  
                }
                else{
                    $builder
                    ->add('componente', EntityType::class, array(
                          'class' => 'MonitorBundle:Componente',
                          'query_builder' => function (EntityRepository $er) use ($filtroComponente) {
                                  return $er->createQueryBuilder('c')
                                            ->where('c.id = ?1')
                                            ->setParameter(1, $filtroComponente)                                       
                                            ->orderBy('c.nombre', 'ASC');
                          }, 
                          'choice_label' => 'nombre',                   
                          //'placeholder' => 'Seleccione una opción...',
                          //'position' => array('after' => 'numeroTicket'),
                          //'disabled' => true, 
                          'data' => $filtroComponente,
                          'choice_attr' => function($val, $key, $index) use ($filtroComponente) {
                                    // adds a class like attending_yes, attending_no, etc                             
                                    //Obtener filtros desde la sesión                             
                                    if($filtroComponente != null){                                                                   
                                         if($val->getId() == $filtroComponente)                                      
                                             return ['selected' => true];
                                         else
                                             return ['selected' => false];
                                    }                                                                                                                     
                          },
                          'label' => false,
                          'attr' => array('style' => 'display:none'),
                    ));                                
                }
                                
                $builder
                ->add('incidencia', EntityType::class, array(
                      'class' => 'MonitorBundle:Incidencia',
                      'query_builder' => function (EntityRepository $er) use ($options) {
                                       return $er->createQueryBuilder('i')                                       
                                       ->where('i.id = ?1')
                                       ->setParameter(1, $options['idIncidencia']);                                              
                      },                                        
                      'choice_label' => 'numeroTicket',                                         
                      'attr' => array('style' => 'display:none'),
                      'label' => false                    
                      //'position' => array('after' => 'numeroRequerimiento'),
                      //'disabled' => true, 
                ))
                ->add('severidad', EntityType::class, array(
                  'class' => 'MonitorBundle:Severidad',
                  'placeholder' => 'Seleccione una opción...',                
                  'query_builder' => function (EntityRepository $er) use ($options) {
                                     return $er->createQueryBuilder('s')
                                               ->join('s.incidencias','i')
                                               ->where('i.id = ?1')
                                               ->setParameter(1, $options['idIncidencia'])
                                               ->orderBy('s.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                //'expanded' => true,
                //'multiple' => false,
                'position' => 'first',
                //'attr' => array('class' => 'form-inline')
                ));                                                                                                         
        }        
        else{ // Origen requerimiento
            $builder        
                ->add('origenMantencion', EntityType::class, array(
                          'class' => 'MonitorBundle:OrigenMantencion',
                          'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('om')
                                      ->orderBy('om.nombre', 'DESC');
                        },
                        'choice_label' => 'nombre',                    
                        'choice_attr' => function($val, $key, $index) {
                             // adds a class like attending_yes, attending_no, etc
                             if($val->getNombre()=='Requerimiento')
                                return ['selected' => true];
                             else
                                return ['selected' => false];
                         },                                                               
                         'attr' => array('style' => 'display:none'),
                         'label' => false                                 
                ));
                         
                //echo  $filtroComponente;
                         
                if($filtroComponente == -1){
                                        
                    $builder
                        ->add('componente', EntityType::class, array(
                              'class' => 'MonitorBundle:Componente',
                              'choice_label' => 'nombre',                   
                              'placeholder' => 'Seleccione una opción...',
                              //'position' => array('after' => 'numeroTicket'),
                              //'disabled' => true, 
                              'data' => $filtroComponente,
                        ));  
                          
                    $formModifierTipo = function (FormInterface $form, Componente $componente = null) {
            
                    $tipos = null === $componente ? array() : $componente->getTiposRequerimiento();             

                    $placeHolder= 'No hay opciones';
                    $disabled = false;                        

                    if($componente!=null){                
                        $disabled = false;
                        $placeHolder= 'Seleccione una opción...';
                    }

                    $form->add('tipoRequerimiento', EntityType::class, array(
                               'class'       => 'MonitorBundle:TipoRequerimiento',
                               'choices'     => $tipos,
                               'choice_label' => function($tipo, $key, $index) {
                                    /** @var Category $category */
                                    return $tipo->getNombre();
                                },
                               'choices_as_values' => true,
                               /*
                               'choice_attr' => function($val, $key, $index) {
                                    // adds a class like attending_yes, attending_no, etc
                                    return ['idTipoAlcance' => $val->getTipoAlcance()->getId()];
                                },                 
                                */                                   
                               'placeholder' => $placeHolder,
                               'disabled' => $disabled,
                               'position' => array('after' => 'componente')
                        ));            
                    };
                    
                    $builder                        
                    ->addEventListener(
                        FormEvents::PRE_SET_DATA,
                        function (FormEvent $event) use ($formModifierTipo, $formModifierInicioProgramado, $options) {                    
                            // this would be your entity, i.e. SportMeetup
                            $data = $event->getData();                                        
                            if($options['idIncidencia'] == null)
                                $formModifierTipo($event->getForm(), $data->getComponente()); 
                            $formModifierInicioProgramado($event->getForm(), $data->getInicioProgramado()); 
                        }
                    );

                    $builder                                    
                    ->get('componente')->addEventListener(
                        FormEvents::POST_SUBMIT,
                        function (FormEvent $event) use ($formModifierTipo) {
                            // It's important here to fetch $event->getForm()->getData(), as
                            // $event->getData() will get you the client data (that is, the ID)
                            $componente = $event->getForm()->getData();

                            // since we've added the listener to the child, we'll have to pass on
                            // the parent to the callback functions!                    
                            $formModifierTipo($event->getForm()->getParent(), $componente);                    
                        }
                    );                    
                }
                else{                    
                    $builder
                    ->add('componente', EntityType::class, array(
                          'class' => 'MonitorBundle:Componente',
                          'query_builder' => function (EntityRepository $er) use ($filtroComponente) {
                                  return $er->createQueryBuilder('c')
                                            ->where('c.id = ?1')
                                            ->setParameter(1, $filtroComponente)                                       
                                            ->orderBy('c.nombre', 'ASC');
                          }, 
                          'choice_label' => 'nombre',                   
                          //'placeholder' => 'Seleccione una opción...',
                          //'position' => array('after' => 'numeroTicket'),
                          //'disabled' => true, 
                          'data' => $filtroComponente,
                          'choice_attr' => function($val, $key, $index) use ($filtroComponente) {
                                    // adds a class like attending_yes, attending_no, etc                             
                                    //Obtener filtros desde la sesión                             
                                    if($filtroComponente != null){                                                                   
                                         if($val->getId() == $filtroComponente)                                      
                                             return ['selected' => true];
                                         else
                                             return ['selected' => false];
                                    }                                                                                                                     
                          },
                          'label' => false,
                          'attr' => array('style' => 'display:none'),
                    ));            
                    $builder->add('tipoRequerimiento', EntityType::class, array(
                                   'class'       => 'MonitorBundle:TipoRequerimiento',
                                   'query_builder' => function (EntityRepository $er) use ($filtroComponente) {
                                      return $er->createQueryBuilder('c')
                                                ->where('c.componente = ?1')
                                                ->setParameter(1, $filtroComponente)                                       
                                                ->orderBy('c.nombre', 'ASC');
                                    },    
                                   //'choices'     => $categorias,
                                   'placeholder' => 'Seleccione una opción...',
                                   'choice_label' => 'nombre',
                                   'choices_as_values' => true,                                    
                    ));    
                }  
                $builder
                ->add('numeroRequerimiento', TextType::class, array(
                      'position' => array('after' => 'tipoRequerimiento'),
                      //'disabled' => true,
                    ))
                ->add('severidad', EntityType::class, array(
                  'class' => 'MonitorBundle:Severidad',
                  'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ei')
                              ->orderBy('ei.nombre', 'ASC');                    
                },
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione una opción...',                
                //'expanded' => true,
                //'multiple' => false,
                'position' => 'first',
                //'attr' => array('class' => 'form-inline')
                ));                                                                           
            }                        
        
        $builder                 
            ->add('tipoMantencion', EntityType::class, array(
                  'class' => 'MonitorBundle:TipoMantencion',
                  'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('om')
                              ->where('om.nombre in (?1)')
                              ->setParameter(1, ['Mantención Correctiva','Mantención Evolutiva'])
                              ->orderBy('om.nombre', 'DESC');

                },
                'choice_label' => 'nombre',
                'expanded' => true,                                                                      
                //'placeholder' => 'Seleccione una opción...',
                'position' => 'first',
                //'attr' => array('class' => 'form-inline')
            ))                    
            ->add('codigoInterno', TextType::class, array(
                  //'position' => array('after' => 'numeroRequerimiento'),
                  //'disabled' => true,
                ));                                                                                                         
            
        $choices = array(            
            'Actual' => 1,
            'Retroactivo' => 2,                    
            'Inicio Programado' => 3                    
        );              
        
        $builder                               
            ->add('tipoIngreso', ChoiceType::class, array(
                'choices' => $choices, 
                'choices_as_values' => true, 
                'expanded' => true,
                'data' => 1,
                'position' => array('after' => 'hhEstimadas'),
                'attr' => array('class' => 'btn-group','data-toggle' => 'buttons')
            ))
            /*
            ->add('fechaInicio', DateTimeType::class, array(
                'date_widget'=> 'single_text',
                'date_format'=>'d/MM/y',
                'data'=> new \DateTime()
                //'disabled' => true
            ))             
             */
            ->add('descripcion', TextareaType::class, array(
                //'disabled' => true
            ))              
            ->add('usuario', EntityType::class, array(
                  'class' => 'MonitorBundle:Usuario',
                  'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('om')                                            
                              ->join('om.area', 'a')
                              ->where('a.nombre in (?1)')
                              ->setParameter(1, ['Desarrollo'])
                              ->orderBy('om.username', 'DESC');
                },
                'choice_label' => 'userName',                                          
                'placeholder' => 'Seleccione una opción...',                
                //'attr' => array('class' => 'form-inline')
            ))                            
            ->add('hhEstimadas', NumberType::class);
            /*
            ->add('estadoMantencion', EntityType::class, array(
                  'class' => 'MonitorBundle:EstadoMantencion',
                  'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ei')
                              ->orderBy('ei.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                //'expanded' => true,
                //'multiple' => false,
                //'position' => 'first',
                //'attr' => array('class' => 'form-inline')
            ));                       
            */                            
         $builder       
            ->add('comentariosMantencion', CollectionType::class, array(
                  'entry_type' => ComentarioMantencionType::class,
                  'data_class' => null,
                  'by_reference' => false,
                  'allow_add'    => true,
                  'allow_delete' => true,
                  'label' => false,
                  'attr' => array('style' => 'display:none'),                  
            ))                           
            ->add('documentosMantencion', CollectionType::class, array(
                  'entry_type' => DocumentoMantencionType::class,
                  'data_class' => null,
                  'by_reference' => false,
                  'allow_add'    => true,
                  'allow_delete' => true,
                  'label' => false,
                  'attr' => array('style' => 'display:none'),                  
            ));                
                
        $builder                                    
            ->get('tipoIngreso')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifierTipoIngreso) {
                    // It's important here to fetch $event->getForm()->getData(), as
                    // $event->getData() will get you the client data (that is, the ID)
                    $tipoIngreso = $event->getForm()->getData();

                    // since we've added the listener to the child, we'll have to pass on
                    // the parent to the callback functions!                    
                    $formModifierTipoIngreso($event->getForm()->getParent(), $tipoIngreso);                    
                }
            );                 
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fonasa\MonitorBundle\Entity\Mantencion',
            'idIncidencia' => 'Symfony\Component\Form\Extension\Core\Type\IntegerType',
            'filtroComponente' => 'Symfony\Component\Form\Extension\Core\Type\IntegerType',
        ));
    }        
}
