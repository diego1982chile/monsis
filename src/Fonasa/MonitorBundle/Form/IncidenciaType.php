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

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Fonasa\MonitorBundle\Entity\OrigenIncidencia;
use Fonasa\MonitorBundle\Entity\Componente;

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
        $builder        
            ->add('id', HiddenType::class);
        
        $builder        
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
            ->add('componente', EntityType::class, array(
                  'class' => 'MonitorBundle:Componente',
                  'choice_label' => 'nombre',                   
                  'placeholder' => 'Seleccione una opción...',
                  'position' => array('after' => 'numeroTicket'),
                  //'disabled' => true, 
            ))
            ->add('numeroTicket', TextType::class, array(
                  'position' => array('after' => 'origenIncidencia'),
                  //'disabled' => true,
                ));
        ;                
        
        $formModifierCategoria = function (FormInterface $form, Componente $componente = null) {
            
            $categorias = null === $componente ? array() : $componente->getCategoriasIncidencia();             
            
            $placeHolder= 'No hay opciones';
            $disabled = false;                        
            
            if($componente!=null){                
                $disabled = false;
                $placeHolder= 'Seleccione una opción...';
            }

            $form->add('categoriaIncidencia', EntityType::class, array(
                       'class'       => 'MonitorBundle:CategoriaIncidencia',
                       'choices'     => $categorias,
                       'choice_label' => function($categoria, $key, $index) {
                            /** @var Category $category */
                            return $categoria->getNombre();
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
        
        $builder                                                    
            ->add('fechaReporte', DateTimeType::class, array(
                'date_widget'=> 'single_text',
                'date_format'=>'d/M/y',
                //'disabled' => true
            ))
            ->add('descripcion', TextareaType::class, array(
                //'disabled' => true
            ))                                
        ;
        
        if($options['assign']==true){
            $builder                                                    
                ->add('hhEstimadas', NumberType::class
            );
        }
        
        /*
        $builder
            ->add('codigoOrigen')
            ->add('codigoInterno')
            ->add('descripcion')
            ->add('fechaReporte', DatetimeType::class)
            ->add('fechaIngreso', DatetimeType::class)
            ->add('fechaSalida', DatetimeType::class)
            ->add('fechaUltHh', DatetimeType::class)
            ->add('idComponente')
            ->add('idOrigenIncidencia')
            ->add('idEstadoIncidencia')
            ->add('idCategoriaIncidencia')
            ->add('componente')
            ->add('origenIncidencia')
            ->add('estadoIncidencia')
            ->add('categoriaIncidencia')
        ;
        */
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fonasa\MonitorBundle\Entity\Incidencia',
            'assign' => [false,true]
        ));
    }
}
