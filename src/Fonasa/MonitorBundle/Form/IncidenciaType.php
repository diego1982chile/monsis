<?php

namespace Fonasa\MonitorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Fonasa\MonitorBundle\Entity\OrigenIncidencia;
use Fonasa\MonitorBundle\Entity\Componente;
use Fonasa\MonitorBundle\Entity\DocumentoIncidencia;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\EntityRepository;


class IncidenciaType extends AbstractType
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
                case 1: // Ingreso actual
                    $form 
                        ->remove('fechaInicio')
                        ->remove('estadoIncidencia');
                    break;
                case 2: // Ingreso retroactivo
                    $form 
                        ->add('fechaInicio', DateTimeType::class, array(
                        'date_widget'=> 'single_text',
                        'date_format'=>'d/MM/y',
                        'data'=> new \DateTime(),
                        //'disabled' => true
                        'attr' => array('style' => 'margin-bottom:14px'),
                        ))
                        ->add('estadoIncidencia', EntityType::class, array(
                            'class' => 'MonitorBundle:EstadoIncidencia',
                            'query_builder' => function (EntityRepository $er) {
                              return $er->createQueryBuilder('ei')
                                        ->where('ei.nombre in (?1)')
                                        ->setParameter(1, ['Pendiente MT', 'En Gestión FONASA', 'Resuelta MT'])  
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
            }                            
        };
        
        $builder        
            ->add('id', HiddenType::class);
                        
            /*
            ->add('origenIncidencia', EntityType::class, array(
                  'class' => 'MonitorBundle:OrigenIncidencia',
                  'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('oi')
                              ->orderBy('oi.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                //'expanded' => true,
                //'multiple' => false,
                'placeholder' => 'Seleccione una opción...',
                'position' => 'first',
                //'attr' => array('class' => 'form-inline')
            ))
            */
            $builder
                ->add('numeroTicket', TextType::class, array(
                      'position' => 'first',
                      //'disabled' => true,
                ));    
        
        if($filtroComponente == -1){
            
            $builder
                ->add('componente', EntityType::class, array(
                      'class' => 'MonitorBundle:Componente',
                      'choice_label' => 'nombre',                   
                      'placeholder' => 'Seleccione una opción...',
                      'position' => array('after' => 'numeroTicket'),
                      //'disabled' => true, 
                      'data' => $filtroComponente,
                ));

            $formModifierCategoria = function (FormInterface $form, Componente $componente = null) {

                $categorias = null === $componente ? array() : $componente->getCategoriasIncidencia();             

                $placeHolder= 'No hay opciones';
                $disabled = false;    
                $idComponente= null;

                //echo $componente->getNombre();

                if($componente!=null){  
                    $idComponente= $componente->getId();
                    $disabled = false;
                    $placeHolder= 'Seleccione una opción...';
                }

                $form->add('categoriaIncidencia', EntityType::class, array(
                           'class'       => 'MonitorBundle:CategoriaIncidencia',
                           'query_builder' => function (EntityRepository $er) use ($idComponente) {
                              return $er->createQueryBuilder('c')
                                        ->where('c.componente = ?1')
                                        ->setParameter(1, $idComponente)                                       
                                        ->orderBy('c.nombre', 'ASC');
                            },               
                           //'choices'     => $categorias,
                           'choice_label' => 'nombre',
                           'choices_as_values' => true,                                                                                                      
                           'placeholder' => $placeHolder,
                           'disabled' => $disabled,
                           'position' => array('after' => 'componente')
                ));            
            }; 
            
            $builder                        
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifierCategoria) {                    
                    // this would be your entity, i.e. SportMeetup
                    $data = $event->getData();                                        
                    
                    $formModifierCategoria($event->getForm(), $data->getComponente());                    
                }
            );
                            
            $builder                                    
            ->get('componente')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifierCategoria) {
                    // It's important here to fetch $event->getForm()->getData(), as
                    // $event->getData() will get you the client data (that is, the ID)
                    $componente = $event->getForm()->getData();

                    // since we've added the listener to the child, we'll have to pass on
                    // the parent to the callback functions!                    
                    $formModifierCategoria($event->getForm()->getParent(), $componente);                    
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
                      'position' => array('after' => 'numeroTicket'),
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
            $builder->add('categoriaIncidencia', EntityType::class, array(
                           'class'       => 'MonitorBundle:CategoriaIncidencia',
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
        ->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifierTipoIngreso, $options) {                    
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();                                        
                $formModifierTipoIngreso($event->getForm(), $data->getTipoIngreso()); 
            }
        );
        
        $choices = array(            
            'Actual' => 1,
            'Retroactivo' => 2
        ); 
        
        $builder                                                    
            /*
            ->add('fechaReporte', DateTimeType::class, array(
                'date_widget'=> 'single_text',
                'date_format'=>'d/M/y',
                //'disabled' => true
            ))
            */
            ->add('descripcion', TextareaType::class, array(
                //'disabled' => true
            ))    
            ->add('severidad', EntityType::class, array(
                  'class' => 'MonitorBundle:Severidad',
                  'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ei')
                              ->orderBy('ei.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                //'expanded' => true,
                //'multiple' => false,
                'placeholder' => 'Seleccione una opción...',                
                'position' => 'first',
                //'attr' => array('class' => 'form-inline')
            ))                 
            ->add('tipoIngreso', ChoiceType::class, array(
                'choices' => $choices, 
                'choices_as_values' => true, 
                'expanded' => true,
                'data' => 1,                
                'attr' => array('class' => 'btn-group','data-toggle' => 'buttons')
            ))
            ->add('comentariosIncidencia', CollectionType::class, array(
                  'entry_type' => ComentarioIncidenciaType::class,
                  'data_class' => null,
                  'by_reference' => false,
                  'allow_add'    => true,
                  'allow_delete' => true,
                  'label' => false,
                  'attr' => array('style' => 'display:none'),                  
            ))                                                
            ->add('documentosIncidencia', CollectionType::class, array(
                  'entry_type' => DocumentoIncidenciaType::class,
                  'data_class' => null,
                  'by_reference' => false,
                  'allow_add'    => true,
                  'allow_delete' => true,
                  'label' => false,
                  'attr' => array('style' => 'display:none'),                  
            ))                        
            ->add('usuario', EntityType::class, array(
                  'class' => 'MonitorBundle:Usuario',
                  'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('om')                                            
                              ->join('om.area', 'a')
                              ->where('a.nombre in (?1)')
                              ->setParameter(1, ['Análisis'])
                              ->orderBy('om.username', 'DESC');
                },
                'choice_label' => 'userName',                                          
                'placeholder' => 'Seleccione una opción...',                
                //'attr' => array('class' => 'form-inline')
            ));
                
        $builder                                    
            ->get('tipoIngreso')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifierTipoIngreso) {
                    // It's important here to fetch $event->getForm()->getData(), as
                    // $event->getData() will get you the client data (that is, the ID)
                    $tipoIngreso = $event->getForm()->getData();
                    
                    //echo "inicioProgramado=".$inicioProgramado;

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
            'data_class' => 'Fonasa\MonitorBundle\Entity\Incidencia',
            'filtroComponente' => 'Symfony\Component\Form\Extension\Core\Type\IntegerType',            
        ));
    }
}
