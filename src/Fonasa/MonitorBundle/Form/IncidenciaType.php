<?php

namespace Fonasa\MonitorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncidenciaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoOrigen')
            ->add('codigoInterno')
            ->add('descripcion')
            ->add('fechaReporte', 'datetime')
            ->add('fechaIngreso', 'datetime')
            ->add('fechaSalida', 'datetime')
            ->add('fechaUltHh', 'datetime')
            ->add('idComponente')
            ->add('idOrigenIncidencia')
            ->add('idEstadoIncidencia')
            ->add('idCategoriaIncidencia')
            ->add('componente')
            ->add('origenIncidencia')
            ->add('estadoIncidencia')
            ->add('categoriaIncidencia')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fonasa\MonitorBundle\Entity\Incidencia'
        ));
    }
}
