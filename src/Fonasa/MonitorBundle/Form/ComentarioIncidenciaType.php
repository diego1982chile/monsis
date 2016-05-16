<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Fonasa\MonitorBundle\Form;

/**
 * Description of DocumentoIncidenciaType
 *
 * @author diego
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\FormInterface;

use Fonasa\MonitorBundle\Entity\TipoDocumentoIncidencia;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ComentarioIncidenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {                    
                    $comentarioIncidencia = $event->getData();
                    $form = $event->getForm();

                    // Check whether the user from the initial data has chosen to
                    // display his email or not.
                    if (!$comentarioIncidencia) {                        
                        $form                            
                            ->add('comentario', TextType::class, array(
                                  'label' => false,
                                  //'data_class' => null,
                                  //'property_path' => 'archivo',                                      
                                  'attr' => array('style' => 'margin-top:0px;margin-bottom:10px'),

                            ));                                                                                      
                    }
                    else
                    {
                        $form
                            ->add('comentario', TextType::class, array(
                                  'label' => false,
                                  //'data_class' => null,
                                  //'attr' => array('style' => 'display:none'),
                                  //'property_path' => 'archivo',                                                          
                                  'attr' => array('style' => 'margin-top:0px;margin-bottom:10px'),
                            ));                           
                        
                    }

                })
                ->getForm();                                                    
    }    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fonasa\MonitorBundle\Entity\ComentarioIncidencia',
            'allow_extra_fields' => true
            //'attr' => array('class' => 'btn btn-default active'),
        ));
    }
}
