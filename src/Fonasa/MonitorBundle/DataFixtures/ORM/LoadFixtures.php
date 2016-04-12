<?php
// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fonasa\MonitorBundle\Entity\Area;
use Fonasa\MonitorBundle\Entity\Estado;
use Fonasa\MonitorBundle\Entity\Origen;
use Fonasa\MonitorBundle\Entity\Prioridad;
use Fonasa\MonitorBundle\Entity\Componente;
use Fonasa\MonitorBundle\Entity\Tarea;
use Fonasa\MonitorBundle\Entity\TareaUsuario;
use Fonasa\MonitorBundle\Entity\Tipo;
use Fonasa\MonitorBundle\Entity\TipoServicio;
use Fonasa\MonitorBundle\Entity\TipoAlcance;
use Fonasa\MonitorBundle\Entity\Alcance;
use Fonasa\MonitorBundle\Entity\Impacto;

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
                
        $connection->exec("ALTER TABLE estado AUTO_INCREMENT = 1;");
        
        $estado1 = new Estado();                
        $estado1->setNombre("En Cola");
        $estado1->setDescripcion("en cola");                
        
        $manager->persist($estado1);
        $manager->flush();   
        
        $estado2 = new Estado();                
        $estado2->setNombre("Análisis");
        $estado2->setDescripcion("en resolución");                
        
        $manager->persist($estado2);
        $manager->flush();           
        
        $estado3 = new Estado();              
        $estado3->setNombre("Desa");
        $estado3->setDescripcion("en desarrollo");                
        
        $manager->persist($estado3);
        $manager->flush();               
                
        $estado4 = new Estado();              
        $estado4->setNombre("Test");
        $estado4->setDescripcion("en testing");                
        
        $manager->persist($estado4);
        $manager->flush();                 
                
        $estado5 = new Estado();              
        $estado5->setNombre("Explotación");
        $estado5->setDescripcion("en certificación");                
        
        $manager->persist($estado5);
        $manager->flush();                       
                
        $estado6 = new Estado();              
        $estado6->setNombre("PaP");
        $estado6->setDescripcion("pendiente PaP");                
        
        $manager->persist($estado6);
        $manager->flush();           
                
        $estado7 = new Estado();              
        $estado7->setNombre("Cerrada");
        $estado7->setDescripcion("Cerrada");                
        
        $manager->persist($estado7);
        $manager->flush();     
        
        $estado8 = new Estado();              
        $estado8->setNombre("en gestión FONASA");
        $estado8->setDescripcion("En Gestión FONASA");                
        
        $manager->persist($estado8);
        $manager->flush();     
        
        $estado9 = new Estado();              
        $estado9->setNombre("pendiente MT");
        $estado9->setDescripcion("Pendiente MT");                
        
        $manager->persist($estado9);
        $manager->flush();             
        
        $estado10 = new Estado();              
        $estado10->setNombre("resuelta MT");
        $estado10->setDescripcion("Resuelta MT");                
        
        $manager->persist($estado10);
        $manager->flush();    
        
        $connection->exec("ALTER TABLE origen AUTO_INCREMENT = 1;");
        
        $origen1 = new Origen();                
        $origen1->setNombre("Req. FONASA");
        $origen1->setDescripcion("Requerimiento FONASA");                
        
        $manager->persist($origen1);
        $manager->flush();         
        
        $origen2 = new Origen();                
        $origen2->setNombre("Req. MINSAL");
        $origen2->setDescripcion("Requerimiento FONASA");                
        
        $manager->persist($origen2);
        $manager->flush();         
                
        $origen3 = new Origen();              
        $origen3->setNombre("Mesa ayuda");
        $origen3->setDescripcion("Mesa de ayuda");                
        
        $manager->persist($origen3);
        $manager->flush();       
        
        $connection->exec("ALTER TABLE prioridad AUTO_INCREMENT = 1;");        
        
        $prioridad1 = new Prioridad();
        $prioridad1->setNombre("Alta");
        $prioridad1->setDescripcion("Prioridad alta");                
        
        $manager->persist($prioridad1);
        $manager->flush();            
        
        $prioridad2 = new Prioridad();
        $prioridad2->setNombre("Media");
        $prioridad2->setDescripcion("Prioridad media");                
        
        $manager->persist($prioridad2);
        $manager->flush();                  
                
        $prioridad3 = new Prioridad();
        $prioridad3->setNombre("Baja");
        $prioridad3->setDescripcion("Prioridad baja");                
        
        $manager->persist($prioridad3);
        $manager->flush();                  
        
        $connection->exec("ALTER TABLE impacto AUTO_INCREMENT = 1;");                        
        
        $impacto1 = new Impacto();
        $impacto1->setNombre("Alto");
        $impacto1->setDescripcion("Alto");                
        
        $manager->persist($impacto1);
        $manager->flush();     
        
        $impacto2 = new Impacto();
        $impacto2->setNombre("Medio");
        $impacto2->setDescripcion("Medio");                
        
        $manager->persist($impacto2);
        $manager->flush();        
        
        $impacto3 = new Impacto();
        $impacto3->setNombre("Leve");
        $impacto3->setDescripcion("Leve");                
        
        $manager->persist($impacto3);
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
        
        $connection->exec("ALTER TABLE tipo AUTO_INCREMENT = 1;");                                                        
        
        $tipo1 = new Tipo();
        $tipo1->setNombre("Incidencia");
        $tipo1->setDescripcion("Incidencia");                
        
        $manager->persist($tipo1);
        $manager->flush();  
        
        $tipo2 = new Tipo();
        $tipo2->setNombre("Mantención Correctiva");
        $tipo2->setDescripcion("Mantención correctiva");                
        
        $manager->persist($tipo2);
        $manager->flush();          
                
        $tipo3 = new Tipo();
        $tipo3->setNombre("Mantención Evolutiva");
        $tipo3->setDescripcion("Mantención evolutiva");                
        
        $manager->persist($tipo3);
        $manager->flush();                  
                
        $tipo4 = new Tipo();     
        $tipo4->setNombre("Mantención Adaptativa");
        $tipo4->setDescripcion("Mantención Adaptativa");                
        
        $manager->persist($tipo4);
        $manager->flush();                   
        
        $connection->exec("ALTER TABLE tipo_servicio AUTO_INCREMENT = 1;");                                                        
                        
        $tipoServicio1 = new TipoServicio();
        $tipoServicio1->setOrigen($origen1);
        $tipoServicio1->setIdOrigen($origen1->getId());        
        $tipoServicio1->setTipo($tipo2);
        $tipoServicio1->setIdTipo($tipo2->getId());        
                
        $manager->persist($tipoServicio1);
        $manager->flush();                       
        
        $tipoServicio2 = new TipoServicio();
        $tipoServicio2->setOrigen($origen1);
        $tipoServicio2->setTipo($tipo3);
        $tipoServicio2->setIdTipo($tipo3->getId());                                     
        
        $manager->persist($tipoServicio2);
        $manager->flush();                
        
        $tipoServicio3 = new TipoServicio();
        $tipoServicio3->setOrigen($origen2);
        $tipoServicio3->setIdOrigen($origen2->getId());        
        $tipoServicio3->setTipo($tipo2);
        $tipoServicio3->setIdTipo($tipo2->getId());                                              
        
        $manager->persist($tipoServicio3);
        $manager->flush();      
        
        $tipoServicio4 = new TipoServicio();
        $tipoServicio4->setOrigen($origen2);
        $tipoServicio4->setIdOrigen($origen2->getId());        
        $tipoServicio4->setTipo($tipo3);
        $tipoServicio4->setIdTipo($tipo3->getId());                                             
        
        $manager->persist($tipoServicio4);
        $manager->flush();            
        
        $tipoServicio5 = new TipoServicio();
        $tipoServicio5->setOrigen($origen3);
        $tipoServicio5->setIdOrigen($origen3->getId());        
        $tipoServicio5->setTipo($tipo1);
        $tipoServicio5->setIdTipo($tipo1->getId());                                             
        
        $manager->persist($tipoServicio5);
        $manager->flush();                    
        
        $tipoServicio6 = new TipoServicio();
        $tipoServicio6->setOrigen($origen3);
        $tipoServicio6->setIdOrigen($origen3->getId());        
        $tipoServicio6->setTipo($tipo2);
        $tipoServicio6->setIdTipo($tipo2->getId());                                             
        
        $manager->persist($tipoServicio6);
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
        
        $connection->exec("ALTER TABLE tipo_alcance AUTO_INCREMENT = 1;");                
        
        $tipoAlcance1 = new TipoAlcance();
        $tipoAlcance1->setNombre("ACR Consulta");
        $tipoAlcance1->setDescripcion("ACR Consulta");                
        
        $manager->persist($tipoAlcance1);
        $manager->flush();                         
        
        $tipoAlcance2 = new TipoAlcance();   
        $tipoAlcance2->setNombre("Adm. Establecimiento");
        $tipoAlcance2->setDescripcion("Adm. Establecimiento");                
        
        $manager->persist($tipoAlcance2);
        $manager->flush();                           
                
        $tipoAlcance3 = new TipoAlcance();
        $tipoAlcance3->setNombre("Adm. Colas");
        $tipoAlcance3->setDescripcion("Adm. Colas");                
        
        $manager->persist($tipoAlcance3);
        $manager->flush();                 
                
        $tipoAlcance4 = new TipoAlcance();
        $tipoAlcance4->setNombre("Arancel");
        $tipoAlcance4->setDescripcion("Arancel");                
        
        $manager->persist($tipoAlcance4);
        $manager->flush();              
                
        $tipoAlcance5 = new TipoAlcance();
        $tipoAlcance5->setNombre("Beneficiario");
        $tipoAlcance5->setDescripcion("Beneficiario");                
        
        $manager->persist($tipoAlcance5);
        $manager->flush();                   
                
        $tipoAlcance6 = new TipoAlcance();
        $tipoAlcance6->setNombre("Búsqueda paciente");
        $tipoAlcance6->setDescripcion("Búsqueda paciente");                
        
        $manager->persist($tipoAlcance6);
        $manager->flush();        
                
        $tipoAlcance7 = new TipoAlcance();
        $tipoAlcance7->setNombre("CAT");
        $tipoAlcance7->setDescripcion("CAT");                
        
        $manager->persist($tipoAlcance7);
        $manager->flush();              
                
        $tipoAlcance8 = new TipoAlcance();
        $tipoAlcance8->setNombre("CUP");
        $tipoAlcance8->setDescripcion("CUP");                
        
        $manager->persist($tipoAlcance8);
        $manager->flush();          
        
        $tipoAlcance9 = new TipoAlcance();
        $tipoAlcance9->setNombre("Datamart");
        $tipoAlcance9->setDescripcion("Datamart");                
        
        $manager->persist($tipoAlcance9);
        $manager->flush();               
                
        $tipoAlcance10 = new TipoAlcance();
        $tipoAlcance10->setNombre("DDE");
        $tipoAlcance10->setDescripcion("DDE");                
        
        $manager->persist($tipoAlcance10);
        $manager->flush();          
        
        $tipoAlcance11 = new TipoAlcance();
        $tipoAlcance11->setNombre("Desbloqueo prev. fallecido");
        $tipoAlcance11->setDescripcion("Desbloqueo prev. fallecido");                
        
        $manager->persist($tipoAlcance11);
        $manager->flush();                  
                
        $tipoAlcance12 = new TipoAlcance();
        $tipoAlcance12->setNombre("ENDECA");
        $tipoAlcance12->setDescripcion("ENDECA");                
        
        $manager->persist($tipoAlcance12);
        $manager->flush();                  
                
        $tipoAlcance13 = new TipoAlcance();
        $tipoAlcance13->setNombre("Extracción");
        $tipoAlcance13->setDescripcion("Extracción");                
        
        $manager->persist($tipoAlcance13);
        $manager->flush();                     
                
        $tipoAlcance14 = new TipoAlcance();
        $tipoAlcance14->setNombre("EXTRAEGGPF");
        $tipoAlcance14->setDescripcion("EXTRAEGGPF");                
        
        $manager->persist($tipoAlcance14);
        $manager->flush();                         
                
        $tipoAlcance15 = new TipoAlcance();
        $tipoAlcance15->setNombre("Facturación");
        $tipoAlcance15->setDescripcion("Facturación");                
        
        $manager->persist($tipoAlcance15);
        $manager->flush();                  
                
        $tipoAlcance16 = new TipoAlcance();
        $tipoAlcance16->setNombre("Parametrización eventos GO");
        $tipoAlcance16->setDescripcion("Parametrización eventos GO");                
        
        $manager->persist($tipoAlcance16);
        $manager->flush();                  
                
        $tipoAlcance17 = new TipoAlcance();
        $tipoAlcance17->setNombre("IFL");
        $tipoAlcance17->setDescripcion("IFL");                
        
        $manager->persist($tipoAlcance17);
        $manager->flush();                 
                
        $tipoAlcance18 = new TipoAlcance();
        $tipoAlcance18->setNombre("Manuales");
        $tipoAlcance18->setDescripcion("Manuales");                
        
        $manager->persist($tipoAlcance18);
        $manager->flush();                         
                
        $tipoAlcance19 = new TipoAlcance();
        $tipoAlcance19->setNombre("Monitoreo y consultas");
        $tipoAlcance19->setDescripcion("Monitoreo y consultas");                
        
        $manager->persist($tipoAlcance19);
        $manager->flush();                       
                
        $tipoAlcance20 = new TipoAlcance();
        $tipoAlcance20->setNombre("Reporte OFF-Line");
        $tipoAlcance20->setDescripcion("Reporte OFF-Line");                
        
        $manager->persist($tipoAlcance20);
        $manager->flush();                               
                
        $tipoAlcance21 = new TipoAlcance();
        $tipoAlcance21->setNombre("RNP");
        $tipoAlcance21->setDescripcion("RNP");                
        
        $manager->persist($tipoAlcance21);
        $manager->flush();                               
                
        $tipoAlcance22 = new TipoAlcance();    
        $tipoAlcance22->setNombre("POII-POIM");
        $tipoAlcance22->setDescripcion("POII-POIM");                
        
        $manager->persist($tipoAlcance22);
        $manager->flush();          
                
        $tipoAlcance23 = new TipoAlcance();
        $tipoAlcance23->setNombre("Recálculo GO");
        $tipoAlcance23->setDescripcion("Recálculo GO");                
        
        $manager->persist($tipoAlcance23);
        $manager->flush();                  
                
        $tipoAlcance24 = new TipoAlcance();
        $tipoAlcance24->setNombre("Revalorizar");
        $tipoAlcance24->setDescripcion("Revalorizar");                
        
        $manager->persist($tipoAlcance24);
        $manager->flush();                          
                
        $tipoAlcance25 = new TipoAlcance();
        $tipoAlcance25->setNombre("Proceso CAC");
        $tipoAlcance25->setDescripcion("Proceso CAC");                
        
        $manager->persist($tipoAlcance25);
        $manager->flush();                          
                
        $tipoAlcance26 = new TipoAlcance();
        $tipoAlcance26->setNombre("VIH");
        $tipoAlcance26->setDescripcion("VIH");                
        
        $manager->persist($tipoAlcance26);
        $manager->flush();                          
                
        $tipoAlcance27 = new TipoAlcance();
        $tipoAlcance27->setNombre("WS Certificador Prev.");
        $tipoAlcance27->setDescripcion("WS Certificador Prev.");                
        
        $manager->persist($tipoAlcance27);
        $manager->flush();                  
        
        $connection->exec("ALTER TABLE alcance AUTO_INCREMENT = 1;");                                
                        
        $alcance1 = new Alcance();
        $alcance1->setComponente($componente1);
        $alcance1->setIdComponente($componente1->getId());
        $alcance1->setTipoAlcance($tipoAlcance1);        
        $alcance1->setIdTipoAlcance($tipoAlcance1->getId());
        
        $manager->persist($alcance1);
        $manager->flush();                          
        
        $alcance2 = new Alcance();
        $alcance2->setComponente($componente1);
        $alcance2->setIdComponente($componente1->getId());
        $alcance2->setTipoAlcance($tipoAlcance2);        
        $alcance2->setIdTipoAlcance($tipoAlcance2->getId());                
        
        $manager->persist($alcance2);
        $manager->flush();                      
                
        $alcance3 = new Alcance();
        $alcance3->setComponente($componente1);
        $alcance3->setIdComponente($componente1->getId());
        $alcance3->setTipoAlcance($tipoAlcance3);        
        $alcance3->setIdTipoAlcance($tipoAlcance3->getId());        
        
        $manager->persist($alcance3);
        $manager->flush();         
        
        $alcance4 = new Alcance();
        $alcance4->setComponente($componente1);
        $alcance4->setIdComponente($componente1->getId());
        $alcance4->setTipoAlcance($tipoAlcance4);        
        $alcance4->setIdTipoAlcance($tipoAlcance4->getId());                
        
        $manager->persist($alcance4);
        $manager->flush();         
        
        $alcance5 = new Alcance();
        $alcance5->setComponente($componente1);
        $alcance5->setIdComponente($componente1->getId());
        $alcance5->setTipoAlcance($tipoAlcance5);        
        $alcance5->setIdTipoAlcance($tipoAlcance5->getId());                        
        
        $manager->persist($alcance5);
        $manager->flush();         
        
        $alcance6 = new Alcance();
        $alcance6->setComponente($componente1);
        $alcance6->setIdComponente($componente1->getId());
        $alcance6->setTipoAlcance($tipoAlcance6);        
        $alcance6->setIdTipoAlcance($tipoAlcance6->getId());                        
        
        $manager->persist($alcance6);
        $manager->flush();         
        
        $alcance7 = new Alcance();
        $alcance7->setComponente($componente1);
        $alcance7->setIdComponente($componente1->getId());
        $alcance7->setTipoAlcance($tipoAlcance7);        
        $alcance7->setIdTipoAlcance($tipoAlcance7->getId());                        
        
        $manager->persist($alcance7);
        $manager->flush();         
        
        $alcance8 = new Alcance();
        $alcance8->setComponente($componente1);
        $alcance8->setIdComponente($componente1->getId());
        $alcance8->setTipoAlcance($tipoAlcance8);        
        $alcance8->setIdTipoAlcance($tipoAlcance8->getId());                        
        
        $manager->persist($alcance8);
        $manager->flush();         
        
        $alcance9 = new Alcance();
        $alcance9->setComponente($componente1);
        $alcance9->setIdComponente($componente1->getId());
        $alcance9->setTipoAlcance($tipoAlcance9);        
        $alcance9->setIdTipoAlcance($tipoAlcance9->getId());                        
        
        $manager->persist($alcance9);
        $manager->flush();         
        
        $alcance10 = new Alcance();
        $alcance10->setComponente($componente1);
        $alcance10->setIdComponente($componente1->getId());
        $alcance10->setTipoAlcance($tipoAlcance10);        
        $alcance10->setIdTipoAlcance($tipoAlcance10->getId());        
                
        $manager->persist($alcance10);
        $manager->flush();          
        
        $alcance11 = new Alcance();
        $alcance11->setComponente($componente1);
        $alcance11->setIdComponente($componente1->getId());
        $alcance11->setTipoAlcance($tipoAlcance11);        
        $alcance11->setIdTipoAlcance($tipoAlcance11->getId());                        
        
        $manager->persist($alcance11);
        $manager->flush();          
        
        $alcance12 = new Alcance();
        $alcance12->setComponente($componente1);
        $alcance12->setIdComponente($componente1->getId());
        $alcance12->setTipoAlcance($tipoAlcance12);        
        $alcance12->setIdTipoAlcance($tipoAlcance12->getId());                                
        
        $manager->persist($alcance12);
        $manager->flush();         
        
        $alcance13 = new Alcance();
        $alcance13->setComponente($componente1);
        $alcance13->setIdComponente($componente1->getId());
        $alcance13->setTipoAlcance($tipoAlcance13);        
        $alcance13->setIdTipoAlcance($tipoAlcance13->getId());                        
        
        $manager->persist($alcance13);
        $manager->flush();      
        
        $alcance14 = new Alcance();
        $alcance14->setComponente($componente1);
        $alcance14->setIdComponente($componente1->getId());
        $alcance14->setTipoAlcance($tipoAlcance14);        
        $alcance14->setIdTipoAlcance($tipoAlcance14->getId());                       
        
        $manager->persist($alcance14);
        $manager->flush();          
        
        $alcance15 = new Alcance();
        $alcance15->setComponente($componente1);
        $alcance15->setIdComponente($componente1->getId());
        $alcance15->setTipoAlcance($tipoAlcance15);        
        $alcance15->setIdTipoAlcance($tipoAlcance15->getId());                                
        
        $manager->persist($alcance15);
        $manager->flush();          
        
        $alcance16 = new Alcance();
        $alcance16->setComponente($componente1);
        $alcance16->setIdComponente($componente1->getId());
        $alcance16->setTipoAlcance($tipoAlcance16);        
        $alcance16->setIdTipoAlcance($tipoAlcance16->getId());                                
        
        $manager->persist($alcance16);
        $manager->flush();          
        
        $alcance17 = new Alcance();
        $alcance17->setComponente($componente1);
        $alcance17->setIdComponente($componente1->getId());
        $alcance17->setTipoAlcance($tipoAlcance17);        
        $alcance17->setIdTipoAlcance($tipoAlcance17->getId());                                
        
        $manager->persist($alcance17);
        $manager->flush();          
        
        $alcance18 = new Alcance();
        $alcance18->setComponente($componente1);
        $alcance18->setIdComponente($componente1->getId());
        $alcance18->setTipoAlcance($tipoAlcance18);        
        $alcance18->setIdTipoAlcance($tipoAlcance18->getId());                                
        
        $manager->persist($alcance18);
        $manager->flush();          
        
        $alcance19 = new Alcance();
        $alcance19->setComponente($componente1);
        $alcance19->setIdComponente($componente1->getId());
        $alcance19->setTipoAlcance($tipoAlcance19);        
        $alcance19->setIdTipoAlcance($tipoAlcance19->getId());                                
        
        $manager->persist($alcance19);
        $manager->flush();                 
        
        $alcance20 = new Alcance();
        $alcance20->setComponente($componente1);
        $alcance20->setIdComponente($componente1->getId());
        $alcance20->setTipoAlcance($tipoAlcance20);        
        $alcance20->setIdTipoAlcance($tipoAlcance20->getId());                               
        
        $manager->persist($alcance20);
        $manager->flush();          
        
        $alcance21 = new Alcance();
        $alcance21->setComponente($componente1);
        $alcance21->setIdComponente($componente1->getId());
        $alcance21->setTipoAlcance($tipoAlcance21);        
        $alcance21->setIdTipoAlcance($tipoAlcance21->getId());                                               
        
        $manager->persist($alcance21);
        $manager->flush();          
        
        $alcance22 = new Alcance();
        $alcance22->setComponente($componente1);
        $alcance22->setIdComponente($componente1->getId());
        $alcance22->setTipoAlcance($tipoAlcance22);        
        $alcance22->setIdTipoAlcance($tipoAlcance22->getId());                                                       
        
        $manager->persist($alcance22);
        $manager->flush();          
        
        $alcance23 = new Alcance();
        $alcance23->setComponente($componente1);
        $alcance23->setIdComponente($componente1->getId());
        $alcance23->setTipoAlcance($tipoAlcance23);        
        $alcance23->setIdTipoAlcance($tipoAlcance23->getId());                                                       
        
        $manager->persist($alcance23);
        $manager->flush();          
        
        $alcance24 = new Alcance();
        $alcance24->setComponente($componente1);
        $alcance24->setIdComponente($componente1->getId());
        $alcance24->setTipoAlcance($tipoAlcance24);        
        $alcance24->setIdTipoAlcance($tipoAlcance24->getId());                                                       
        
        $manager->persist($alcance24);
        $manager->flush();          
        
        $alcance25 = new Alcance();
        $alcance25->setComponente($componente1);
        $alcance25->setIdComponente($componente1->getId());
        $alcance25->setTipoAlcance($tipoAlcance25);        
        $alcance25->setIdTipoAlcance($tipoAlcance25->getId());                                                       
        
        $manager->persist($alcance25);
        $manager->flush();          
        
        $alcance26 = new Alcance();
        $alcance26->setComponente($componente1);
        $alcance26->setIdComponente($componente1->getId());
        $alcance26->setTipoAlcance($tipoAlcance26);        
        $alcance26->setIdTipoAlcance($tipoAlcance26->getId());                                                               
        
        $manager->persist($alcance26);
        $manager->flush();          
        
        $alcance27 = new Alcance();
        $alcance27->setComponente($componente1);
        $alcance27->setIdComponente($componente1->getId());
        $alcance27->setTipoAlcance($tipoAlcance27);        
        $alcance27->setIdTipoAlcance($tipoAlcance27->getId());                                               
        
        $manager->persist($alcance27);
        $manager->flush();     
        
    }
}
