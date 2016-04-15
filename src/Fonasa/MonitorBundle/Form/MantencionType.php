<?php

namespace Fonasa\MonitorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MantencionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $origenes = array(            
            'Requerimiento' => 1,
            'Incidencia' => 2                    
        );    
        
        $builder
            ->add('origenMantencion', ChoiceType::class, array('choices' => $origenes, 'expanded' => true, 'data' => 1, 'attr' => array('class' => 'btn-group','data-toggle' => 'buttons')))
            ->add('codigoInterno')
            ->add('descripcion')
            //->add('fechaIngreso', 'datetime')
            //->add('fechaInicio', 'datetime')
            //->add('fechaSalida', 'datetime')
            //->add('fechaUltHh', 'datetime')
            ->add('idComponente')
            ->add('idTipoMantencion')
            ->add('idEstadoMantencion')
            ->add('idCategoriaMantencion')
            ->add('idIncidencia')
            ->add('idRequerimiento')
            ->add('componente')
            ->add('tipoMantencion')
            ->add('estadoMantencion')
            ->add('categoriaMantencion')
            ->add('incidencia')
            ->add('rquerimiento')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fonasa\MonitorBundle\Entity\Mantencion'
        ));
    }        
}
