<?php
// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fonasa\MonitorBundle\Entity\Area;
use Fonasa\MonitorBundle\Entity\EstadoIncidencia;
use Fonasa\MonitorBundle\Entity\EstadoMantencion;
use Fonasa\MonitorBundle\Entity\OrigenIncidencia;
use Fonasa\MonitorBundle\Entity\SeveridadMantencion;
use Fonasa\MonitorBundle\Entity\Componente;
use Fonasa\MonitorBundle\Entity\Tarea;
use Fonasa\MonitorBundle\Entity\TipoMantencion;
use Fonasa\MonitorBundle\Entity\TareaUsuario;
use Fonasa\MonitorBundle\Entity\CategoriaIncidencia;
use Fonasa\MonitorBundle\Entity\CategoriaMantencion;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoadFixtures extends Controller implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        
        $connection->exec("ALTER TABLE area AUTO_INCREMENT = 1;");
        
        $area1 = new Area();
                
        $area1->setNombre("Análisis");
        $area1->setDescripcion("Área encargada de analizar y gestionar los requerimientos y/o incidencias, generando servicios asociados");        
        
        $manager->persist($area1);        

        $area2 = new Area();                
        $area2->setNombre("Desarrollo");        
        $area2->setDescripcion("Área encargada de desarrollar las mantenciones generadas por el área de análisis");        

        $manager->persist($area2);        
                
        $area3 = new Area();                
        $area3->setNombre("Testing");
        $area3->setDescripcion("Área encargada de testear las mantenciones generadas por el área de desarrollo");                        

        $manager->persist($area3);         
                
        $area4 = new Area();                
        $area4->setNombre("Explotación");
        $area4->setDescripcion("Área encargada de certificar las mantenciones generadas por testing y realizar PaP");                
        
        $manager->persist($area4);
        $manager->flush(); 
                
        $connection->exec("ALTER TABLE estado_incidencia AUTO_INCREMENT = 1;");
        
        $estadoIncidencia1 = new EstadoIncidencia();                
        $estadoIncidencia1->setNombre("En Cola");
        $estadoIncidencia1->setDescripcion("en cola");                
        
        $manager->persist($estadoIncidencia1);
        $manager->flush();   
        
        $estadoIncidencia2 = new EstadoIncidencia();                
        $estadoIncidencia2->setNombre("En Gestión FONASA");
        $estadoIncidencia2->setDescripcion("En Gestión FONASA");                
        
        $manager->persist($estadoIncidencia2);
        $manager->flush();           
        
        $estadoIncidencia3 = new EstadoIncidencia();              
        $estadoIncidencia3->setNombre("Pendiente MT");
        $estadoIncidencia3->setDescripcion("Pendiente MT");                
        
        $manager->persist($estadoIncidencia3);
        $manager->flush();               
                
        $estadoIncidencia4 = new EstadoIncidencia();              
        $estadoIncidencia4->setNombre("Resuelta MT");
        $estadoIncidencia4->setDescripcion("Resuelta MT");                
        
        $manager->persist($estadoIncidencia4);
        $manager->flush();                 
                
        $estadoMantencion1 = new EstadoMantencion();              
        $estadoMantencion1->setNombre("En cola");
        $estadoMantencion1->setDescripcion("En cola");                
        
        $manager->persist($estadoMantencion1);
        $manager->flush();                       
                
        $estadoMantencion2 = new EstadoMantencion();              
        $estadoMantencion2->setNombre("Desa");
        $estadoMantencion2->setDescripcion("En Desarrollo");                
        
        $manager->persist($estadoMantencion2);
        $manager->flush();                       
        
        $estadoMantencion3 = new EstadoMantencion();              
        $estadoMantencion3->setNombre("Test");
        $estadoMantencion3->setDescripcion("En Testing");                
        
        $manager->persist($estadoMantencion3);
        $manager->flush();                                               
        
        $estadoMantencion5 = new EstadoMantencion();              
        $estadoMantencion5->setNombre("PaP");
        $estadoMantencion5->setDescripcion("Pendiente PaP");                
        
        $manager->persist($estadoMantencion5);
        $manager->flush();  
        
        $estadoMantencion6 = new EstadoMantencion();              
        $estadoMantencion6->setNombre("Cerrada");
        $estadoMantencion6->setDescripcion("Cerrada");                
        
        $manager->persist($estadoMantencion6);
        $manager->flush();          
        
        $connection->exec("ALTER TABLE origen_incidencia AUTO_INCREMENT = 1;");
        
        $origen1 = new OrigenIncidencia();                
        $origen1->setNombre("MAF");
        $origen1->setDescripcion("Mesa Ayuda FONASA");                
        
        $manager->persist($origen1);
        $manager->flush();         
        
        $origen2 = new OrigenIncidencia();                
        $origen2->setNombre("MAM");
        $origen2->setDescripcion("MAM");                
        
        $manager->persist($origen2);
        $manager->flush();         
                        
        $connection->exec("ALTER TABLE severidad_mantencion AUTO_INCREMENT = 1;");        
        
        $severidad1 = new SeveridadMantencion();
        $severidad1->setNombre("Alta");
        $severidad1->setDescripcion("Severidad alta");                
        
        $manager->persist($severidad1);
        $manager->flush();            
        
        $severidad2 = new SeveridadMantencion();
        $severidad2->setNombre("Media");
        $severidad2->setDescripcion("Severidad media");                
        
        $manager->persist($severidad2);
        $manager->flush();            
        
        $severidad3 = new SeveridadMantencion();
        $severidad3->setNombre("Baja");
        $severidad3->setDescripcion("Severidad baja");                
        
        $manager->persist($severidad3);
        $manager->flush();                    
                
        $connection->exec("ALTER TABLE componente AUTO_INCREMENT = 1;");                                        
        
        $componente1 = new Componente();
        $componente1->setNombre("SIGGES");
        $componente1->setDescripcion("Componente de monitoreo de Garantias de Oportunidad");                
        
        $manager->persist($componente1);
        $manager->flush();        
                
        $componente2 = new Componente();
        $componente2->setNombre("GGPF");
        $componente2->setDescripcion("Componente de monitoreo de Garantias financieras");                                
        
        $manager->persist($componente2);
        $manager->flush();                
        
        $componente3 = new Componente();
        $componente3->setNombre("Préstamo Médico");
        $componente3->setDescripcion("Préstamo Médico");                                
        
        $manager->persist($componente3);
        $manager->flush();                
        
        $componente4 = new Componente();
        $componente4->setNombre("DWH");
        $componente4->setDescripcion("DWH");                                
        
        $manager->persist($componente4);
        $manager->flush();                        
        
        $connection->exec("ALTER TABLE tipo_mantencion AUTO_INCREMENT = 1;");                                                                        
        
        $tipo2 = new TipoMantencion();
        $tipo2->setNombre("Mantención Correctiva");
        $tipo2->setDescripcion("Mantención correctiva");                
        
        $manager->persist($tipo2);
        $manager->flush();          
                
        $tipo3 = new TipoMantencion();
        $tipo3->setNombre("Mantención Evolutiva");
        $tipo3->setDescripcion("Mantención evolutiva");                
        
        $manager->persist($tipo3);
        $manager->flush();                  
                
        $tipo4 = new TipoMantencion();     
        $tipo4->setNombre("Mantención Adaptativa");
        $tipo4->setDescripcion("Mantención Adaptativa");                
        
        $manager->persist($tipo4);
        $manager->flush();                   
                
        $connection->exec("ALTER TABLE tarea AUTO_INCREMENT = 1;");                                                                        
        
        $tarea1 = new Tarea();
        $tarea1->setNombre("Análisis y diseño");
        $tarea1->setDescripcion("Análisis y diseño");                
        
        $manager->persist($tarea1);
        $manager->flush();  
                
        $tarea2 = new Tarea();
        $tarea2->setNombre("Preparación Scripts");
        $tarea2->setDescripcion("Preparación Scripts");                
        
        $manager->persist($tarea2);
        $manager->flush();          
                
        $tarea3 = new Tarea();
        $tarea3->setNombre("Preparación Documentación");
        $tarea3->setDescripcion("Preparación Documentación");                
        
        $manager->persist($tarea3);
        $manager->flush();          
                
        $tarea4 = new Tarea();
        $tarea4->setNombre("Generación WAR y CD");
        $tarea4->setDescripcion("Generación WAR y CD");                
        
        $manager->persist($tarea4);
        $manager->flush();  
                
        $tarea5 = new Tarea();
        $tarea5->setNombre("Revisión documentación");
        $tarea5->setDescripcion("Revisión documentación");                
        
        $manager->persist($tarea5);
        $manager->flush();             
                
        $tarea6 = new Tarea();
        $tarea6->setNombre("Revisión Scripts");
        $tarea6->setDescripcion("Revisión Scripts");                
        
        $manager->persist($tarea6);
        $manager->flush();     
                
        $tarea7 = new Tarea();
        $tarea7->setNombre("Compare Versiones");
        $tarea7->setDescripcion("Compare Versiones");                
        
        $manager->persist($tarea7);
        $manager->flush();             
                
        $tarea8 = new Tarea();
        $tarea8->setNombre("Instalación WAR");
        $tarea8->setDescripcion("Instalación WAR");                
        
        $manager->persist($tarea8);
        $manager->flush();             
                
        $tarea9 = new Tarea();
        $tarea9->setNombre("Pruebas");
        $tarea9->setDescripcion("Set de pruebas");                
        
        $manager->persist($tarea9);
        $manager->flush();                    
                
        $tarea10 = new Tarea();
        $tarea10->setNombre("Certificación Scripts");
        $tarea10->setDescripcion("Certificación scripts");                
        
        $manager->persist($tarea10);
        $manager->flush();          
        
        $connection->exec("ALTER TABLE tarea_usuario AUTO_INCREMENT = 1;");                                                                                        
        
        $tareaUsuario1 = new TareaUsuario();
        $tareaUsuario1->setArea($area2);
        $tareaUsuario1->setIdArea($area2->getId());        
        $tareaUsuario1->setTarea($tarea1);
        $tareaUsuario1->setIdTarea($tarea1->getId());             
        
        $manager->persist($tareaUsuario1);
        $manager->flush();            
        
        $tareaUsuario2 = new TareaUsuario();
        $tareaUsuario2->setArea($area2);
        $tareaUsuario2->setIdArea($area2->getId());        
        $tareaUsuario2->setTarea($tarea2);
        $tareaUsuario2->setIdTarea($tarea2->getId());                     
        
        $manager->persist($tareaUsuario2);
        $manager->flush();         
        
        $tareaUsuario3 = new TareaUsuario();
        $tareaUsuario3->setArea($area2);
        $tareaUsuario3->setIdArea($area2->getId());        
        $tareaUsuario3->setTarea($tarea3);
        $tareaUsuario3->setIdTarea($tarea3->getId());                     
        
        $manager->persist($tareaUsuario3);
        $manager->flush();         
        
        $tareaUsuario4 = new TareaUsuario();
        $tareaUsuario4->setArea($area2);
        $tareaUsuario4->setIdArea($area2->getId());        
        $tareaUsuario4->setTarea($tarea4);
        $tareaUsuario4->setIdTarea($tarea4->getId());                     
        
        $manager->persist($tareaUsuario4);
        $manager->flush();         
        
        $tareaUsuario5 = new TareaUsuario();
        $tareaUsuario5->setArea($area3);
        $tareaUsuario5->setIdArea($area3->getId());        
        $tareaUsuario5->setTarea($tarea5);
        $tareaUsuario5->setIdTarea($tarea5->getId());                     
        
        $manager->persist($tareaUsuario5);
        $manager->flush();         
        
        $tareaUsuario6 = new TareaUsuario();
        $tareaUsuario6->setArea($area3);
        $tareaUsuario6->setIdArea($area3->getId());        
        $tareaUsuario6->setTarea($tarea6);
        $tareaUsuario6->setIdTarea($tarea6->getId());                             
        
        $manager->persist($tareaUsuario6);
        $manager->flush();         
        
        $tareaUsuario7 = new TareaUsuario();
        $tareaUsuario7->setArea($area3);
        $tareaUsuario7->setIdArea($area3->getId());        
        $tareaUsuario7->setTarea($tarea7);
        $tareaUsuario7->setIdTarea($tarea7->getId());                                     
        
        $manager->persist($tareaUsuario7);
        $manager->flush();         
        
        $tareaUsuario8 = new TareaUsuario();
        $tareaUsuario8->setArea($area3);
        $tareaUsuario8->setIdArea($area3->getId());        
        $tareaUsuario8->setTarea($tarea8);
        $tareaUsuario8->setIdTarea($tarea8->getId());             
        
        $manager->persist($tareaUsuario8);
        $manager->flush();         
        
        $tareaUsuario9 = new TareaUsuario();
        $tareaUsuario9->setArea($area3);
        $tareaUsuario9->setIdArea($area3->getId());        
        $tareaUsuario9->setTarea($tarea9);
        $tareaUsuario9->setIdTarea($tarea9->getId());                     
        
        $manager->persist($tareaUsuario9);
        $manager->flush();         
        
        $tareaUsuario10 = new TareaUsuario();
        $tareaUsuario10->setArea($area4);
        $tareaUsuario10->setIdArea($area4->getId());        
        $tareaUsuario10->setTarea($tarea10);
        $tareaUsuario10->setIdTarea($tarea10->getId());                             
        
        $manager->persist($tareaUsuario10);
        $manager->flush();    
        
        $connection->exec("ALTER TABLE categoria_incidencia AUTO_INCREMENT = 1;");                
        
        $tipoAlcance1 = new CategoriaIncidencia();
        $tipoAlcance1->setNombre("Corrección de datos Paciente");
        $tipoAlcance1->setDescripcion("Corrección de datos Paciente");                
        $tipoAlcance1->setComponente($componente1);
        $tipoAlcance1->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance1);
        $manager->flush();                         
        
        $tipoAlcance2 = new CategoriaIncidencia();   
        $tipoAlcance2->setNombre("Extracción");
        $tipoAlcance2->setDescripcion("Extracción");                
        $tipoAlcance2->setComponente($componente1);
        $tipoAlcance2->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance2);
        $manager->flush();                          
        
        $connection->exec("ALTER TABLE categoria_mantencion AUTO_INCREMENT = 1;");                
        
        $tipoAlcance1 = new CategoriaMantencion();
        $tipoAlcance1->setNombre("ACR Consulta");
        $tipoAlcance1->setDescripcion("ACR Consulta");                
        $tipoAlcance1->setComponente($componente1);
        $tipoAlcance1->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance1);
        $manager->flush();                         
        
        $tipoAlcance2 = new CategoriaMantencion();   
        $tipoAlcance2->setNombre("Adm. Establecimiento");
        $tipoAlcance2->setDescripcion("Adm. Establecimiento");                
        $tipoAlcance2->setComponente($componente1);
        $tipoAlcance2->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance2);
        $manager->flush();                           
                
        $tipoAlcance3 = new CategoriaMantencion();
        $tipoAlcance3->setNombre("Adm. Colas");
        $tipoAlcance3->setDescripcion("Adm. Colas");          
        $tipoAlcance3->setComponente($componente1);
        $tipoAlcance3->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance3);
        $manager->flush();                 
                
        $tipoAlcance4 = new CategoriaMantencion();
        $tipoAlcance4->setNombre("Arancel");
        $tipoAlcance4->setDescripcion("Arancel");                
        $tipoAlcance4->setComponente($componente1);
        $tipoAlcance4->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance4);
        $manager->flush();              
                
        $tipoAlcance5 = new CategoriaMantencion();
        $tipoAlcance5->setNombre("Beneficiario");
        $tipoAlcance5->setDescripcion("Beneficiario");        
        $tipoAlcance5->setComponente($componente1);
        $tipoAlcance5->setIdComponente($componente1->getId());        
        
        $manager->persist($tipoAlcance5);
        $manager->flush();                   
                
        $tipoAlcance6 = new CategoriaMantencion();
        $tipoAlcance6->setNombre("Búsqueda paciente");
        $tipoAlcance6->setDescripcion("Búsqueda paciente");                
        $tipoAlcance6->setComponente($componente1);
        $tipoAlcance6->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance6);
        $manager->flush();        
                
        $tipoAlcance7 = new CategoriaMantencion();
        $tipoAlcance7->setNombre("CAT");
        $tipoAlcance7->setDescripcion("CAT");                
        $tipoAlcance7->setComponente($componente1);
        $tipoAlcance7->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance7);
        $manager->flush();              
                
        $tipoAlcance8 = new CategoriaMantencion();
        $tipoAlcance8->setNombre("CUP");
        $tipoAlcance8->setDescripcion("CUP");                
        $tipoAlcance8->setComponente($componente1);
        $tipoAlcance8->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance8);
        $manager->flush();          
        
        $tipoAlcance9 = new CategoriaMantencion();
        $tipoAlcance9->setNombre("Datamart");
        $tipoAlcance9->setDescripcion("Datamart");            
        $tipoAlcance9->setComponente($componente1);
        $tipoAlcance9->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance9);
        $manager->flush();               
                
        $tipoAlcance10 = new CategoriaMantencion();
        $tipoAlcance10->setNombre("DDE");
        $tipoAlcance10->setDescripcion("DDE");                
        $tipoAlcance10->setComponente($componente1);
        $tipoAlcance10->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance10);
        $manager->flush();          
        
        $tipoAlcance11 = new CategoriaMantencion();
        $tipoAlcance11->setNombre("Desbloqueo prev. fallecido");
        $tipoAlcance11->setDescripcion("Desbloqueo prev. fallecido");                
        $tipoAlcance11->setComponente($componente1);
        $tipoAlcance11->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance11);
        $manager->flush();                  
                
        $tipoAlcance12 = new CategoriaMantencion();
        $tipoAlcance12->setNombre("ENDECA");
        $tipoAlcance12->setDescripcion("ENDECA");             
        $tipoAlcance12->setComponente($componente1);
        $tipoAlcance12->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance12);
        $manager->flush();                  
                
        $tipoAlcance13 = new CategoriaMantencion();
        $tipoAlcance13->setNombre("Extracción");
        $tipoAlcance13->setDescripcion("Extracción");         
        $tipoAlcance13->setComponente($componente1);
        $tipoAlcance13->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance13);
        $manager->flush();                     
                
        $tipoAlcance14 = new CategoriaMantencion();
        $tipoAlcance14->setNombre("EXTRAEGGPF");
        $tipoAlcance14->setDescripcion("EXTRAEGGPF");         
        $tipoAlcance14->setComponente($componente1);
        $tipoAlcance14->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance14);
        $manager->flush();                         
                
        $tipoAlcance15 = new CategoriaMantencion();
        $tipoAlcance15->setNombre("Facturación");
        $tipoAlcance15->setDescripcion("Facturación");        
        $tipoAlcance15->setComponente($componente1);
        $tipoAlcance15->setIdComponente($componente1->getId());        
        
        $manager->persist($tipoAlcance15);
        $manager->flush();                  
                
        $tipoAlcance16 = new CategoriaMantencion();
        $tipoAlcance16->setNombre("Parametrización eventos GO");
        $tipoAlcance16->setDescripcion("Parametrización eventos GO");                
        $tipoAlcance16->setComponente($componente1);
        $tipoAlcance16->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance16);
        $manager->flush();                  
                
        $tipoAlcance17 = new CategoriaMantencion();
        $tipoAlcance17->setNombre("IFL");
        $tipoAlcance17->setDescripcion("IFL");                
        $tipoAlcance17->setComponente($componente1);
        $tipoAlcance17->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance17);
        $manager->flush();                 
                
        $tipoAlcance18 = new CategoriaMantencion();
        $tipoAlcance18->setNombre("Manuales");
        $tipoAlcance18->setDescripcion("Manuales");           
        $tipoAlcance18->setComponente($componente1);
        $tipoAlcance18->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance18);
        $manager->flush();                         
                
        $tipoAlcance19 = new CategoriaMantencion();
        $tipoAlcance19->setNombre("Monitoreo y consultas");
        $tipoAlcance19->setDescripcion("Monitoreo y consultas");                
        $tipoAlcance19->setComponente($componente1);
        $tipoAlcance19->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance19);
        $manager->flush();                       
                
        $tipoAlcance20 = new CategoriaMantencion();
        $tipoAlcance20->setNombre("Reporte OFF-Line");
        $tipoAlcance20->setDescripcion("Reporte OFF-Line");   
        $tipoAlcance20->setComponente($componente1);
        $tipoAlcance20->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance20);
        $manager->flush();                               
                
        $tipoAlcance21 = new CategoriaMantencion();
        $tipoAlcance21->setNombre("RNP");
        $tipoAlcance21->setDescripcion("RNP");                
        $tipoAlcance21->setComponente($componente1);
        $tipoAlcance21->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance21);
        $manager->flush();                               
                
        $tipoAlcance22 = new CategoriaMantencion();    
        $tipoAlcance22->setNombre("POII-POIM");
        $tipoAlcance22->setDescripcion("POII-POIM");          
        $tipoAlcance22->setComponente($componente1);
        $tipoAlcance22->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance22);
        $manager->flush();          
                
        $tipoAlcance23 = new CategoriaMantencion();
        $tipoAlcance23->setNombre("Recálculo GO");
        $tipoAlcance23->setDescripcion("Recálculo GO");       
        $tipoAlcance23->setComponente($componente1);
        $tipoAlcance23->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance23);
        $manager->flush();                  
                
        $tipoAlcance24 = new CategoriaMantencion();
        $tipoAlcance24->setNombre("Revalorizar");
        $tipoAlcance24->setDescripcion("Revalorizar");        
        $tipoAlcance24->setComponente($componente1);
        $tipoAlcance24->setIdComponente($componente1->getId());        
        
        $manager->persist($tipoAlcance24);
        $manager->flush();                          
                
        $tipoAlcance25 = new CategoriaMantencion();
        $tipoAlcance25->setNombre("Proceso CAC");
        $tipoAlcance25->setDescripcion("Proceso CAC");                
        $tipoAlcance25->setComponente($componente1);
        $tipoAlcance25->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance25);
        $manager->flush();                          
                
        $tipoAlcance26 = new CategoriaMantencion();
        $tipoAlcance26->setNombre("VIH");
        $tipoAlcance26->setDescripcion("VIH");                
        $tipoAlcance26->setComponente($componente1);
        $tipoAlcance26->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance26);
        $manager->flush();                          
                
        $tipoAlcance27 = new CategoriaMantencion();
        $tipoAlcance27->setNombre("WS Certificador Prev.");
        $tipoAlcance27->setDescripcion("WS Certificador Prev.");                
        $tipoAlcance27->setComponente($componente1);
        $tipoAlcance27->setIdComponente($componente1->getId());
        
        $manager->persist($tipoAlcance27);
        $manager->flush();                                                                                                 
        
    }
}
