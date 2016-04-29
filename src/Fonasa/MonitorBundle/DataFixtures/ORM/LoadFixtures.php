<?php
// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fonasa\MonitorBundle\Entity\Area;
use Fonasa\MonitorBundle\Entity\Usuario;
use Fonasa\MonitorBundle\Entity\EstadoIncidencia;
use Fonasa\MonitorBundle\Entity\EstadoMantencion;
use Fonasa\MonitorBundle\Entity\OrigenIncidencia;
use Fonasa\MonitorBundle\Entity\OrigenMantencion;
use Fonasa\MonitorBundle\Entity\Severidad;
use Fonasa\MonitorBundle\Entity\Componente;
use Fonasa\MonitorBundle\Entity\Tarea;
use Fonasa\MonitorBundle\Entity\TipoMantencion;
use Fonasa\MonitorBundle\Entity\TareaUsuario;
use Fonasa\MonitorBundle\Entity\CategoriaIncidencia;
use Fonasa\MonitorBundle\Entity\TipoRequerimiento;

use Fonasa\MonitorBundle\Entity\Incidencia;
use Fonasa\MonitorBundle\Entity\Mantencion;

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
        
        $connection->exec("ALTER TABLE estado_mantencion AUTO_INCREMENT = 1;");
                
        $estadoMantencion1 = new EstadoMantencion();              
        $estadoMantencion1->setNombre("En cola");
        $estadoMantencion1->setDescripcion("En cola");                
        
        $manager->persist($estadoMantencion1);
        $manager->flush();                       
                
        $estadoMantencion2 = new EstadoMantencion();              
        $estadoMantencion2->setNombre("En Desarrollo");
        $estadoMantencion2->setDescripcion("En Desarrollo");                
        
        $manager->persist($estadoMantencion2);
        $manager->flush();                       
        
        $estadoMantencion3 = new EstadoMantencion();              
        $estadoMantencion3->setNombre("En Testing");
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
        
        $connection->exec("ALTER TABLE usuario AUTO_INCREMENT = 1;");
        $userManager = $this->container->get('fos_user.user_manager');
        
        $usuario1 = new Usuario();
                
        $usuario1->setUserName("dsoto");
        $usuario1->setEmail("desa1@example.com");
        $usuario1->setPlainPassword('123');
        $usuario1->setArea($area2);
        $usuario1->setEstadoMantencion($estadoMantencion2);
        $userManager->updateUser($usuario1,false);
        
        $usuario2 = new Usuario();
        
        $usuario2->setUserName("rmercado");
        $usuario2->setEmail("desa2@example.com");
        $usuario2->setPlainPassword('123');
        $usuario2->setArea($area2);
        $usuario2->setEstadoMantencion($estadoMantencion2);
        $userManager->updateUser($usuario2,false);
        
        $usuario3 = new Usuario();
        
        $usuario3->setUserName("mvillarroel");
        $usuario3->setEmail("test@example.com");
        $usuario3->setPlainPassword('123');
        $usuario3->setArea($area3);
        $usuario3->setEstadoMantencion($estadoMantencion3);
        $userManager->updateUser($usuario3,false);                                                
        
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
        
        $connection->exec("ALTER TABLE origen_mantencion AUTO_INCREMENT = 1;");
        
        $origen1 = new OrigenMantencion();                
        $origen1->setNombre("Incidencia");                    
        
        $manager->persist($origen1);
        $manager->flush();         
        
        $origen2 = new OrigenMantencion();                
        $origen2->setNombre("Requerimiento");        
        
        $manager->persist($origen2);
        $manager->flush();                 
                        
        $connection->exec("ALTER TABLE severidad AUTO_INCREMENT = 1;");        
        
        $severidad1 = new Severidad();
        $severidad1->setNombre("Alta");
        $severidad1->setDescripcion("Severidad alta");
        $severidad1->setSla(1);
        
        
        $manager->persist($severidad1);
        $manager->flush();            
        
        $severidad2 = new Severidad();
        $severidad2->setNombre("Media");
        $severidad2->setDescripcion("Severidad media");                
        $severidad2->setSla(2);
        
        $manager->persist($severidad2);
        $manager->flush();            
        
        $severidad3 = new Severidad();
        $severidad3->setNombre("Baja");
        $severidad3->setDescripcion("Severidad baja");                
        $severidad3->setSla(3);
        
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
        $componente3->setNombre("PM");
        $componente3->setDescripcion("Préstamo Médico");                                
        
        $manager->persist($componente3);
        $manager->flush();                
        
        $componente4 = new Componente();
        $componente4->setNombre("BGI / DataWareHouse");
        $componente4->setDescripcion("BGI / DataWareHouse");                                
        
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
        
        //-------------------INCIDENCIAS SIGGES---------------------------
        $categoriasIncidenciasSigges= array();
        
        $tipoAlcance1 = new CategoriaIncidencia();
        $tipoAlcance1->setNombre("Error/ Falla Acceso");
        $tipoAlcance1->setDescripcion("Crear Acceso");                
        $tipoAlcance1->setComponente($componente1);
        $tipoAlcance1->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[0]=$tipoAlcance1;
        
        $manager->persist($tipoAlcance1);
        $manager->flush();                         
        
        $tipoAlcance2 = new CategoriaIncidencia();   
        $tipoAlcance2->setNombre("Solicitud Clave");
        $tipoAlcance2->setDescripcion("Solicitud Clave");                
        $tipoAlcance2->setComponente($componente1);
        $tipoAlcance2->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[1]=$tipoAlcance2;
        
        $manager->persist($tipoAlcance2);
        $manager->flush();                          
        
        $tipoAlcance3 = new CategoriaIncidencia();   
        $tipoAlcance3->setNombre("Actualizar Prevision");
        $tipoAlcance3->setDescripcion("Actualizar Prevision");                
        $tipoAlcance3->setComponente($componente1);
        $tipoAlcance3->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[2]=$tipoAlcance3;
        
        $manager->persist($tipoAlcance3);
        $manager->flush();                                  
        
        $tipoAlcance4 = new CategoriaIncidencia();   
        $tipoAlcance4->setNombre("Solicitud Clave");
        $tipoAlcance4->setDescripcion("Solicitud Clave");                
        $tipoAlcance4->setComponente($componente1);
        $tipoAlcance4->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[3]=$tipoAlcance4;
        
        $manager->persist($tipoAlcance4);
        $manager->flush();                          
        
        $tipoAlcance5 = new CategoriaIncidencia();   
        $tipoAlcance5->setNombre("Duplicidad de Caso");
        $tipoAlcance5->setDescripcion("Duplicidad de Caso");                
        $tipoAlcance5->setComponente($componente1);
        $tipoAlcance5->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[4]=$tipoAlcance5;
        
        $manager->persist($tipoAlcance5);
        $manager->flush();                                  

        $tipoAlcance6 = new CategoriaIncidencia();   
        $tipoAlcance6->setNombre("Falla en carga de formulario");
        $tipoAlcance6->setDescripcion("Falla en carga de formulario");                
        $tipoAlcance6->setComponente($componente1);
        $tipoAlcance6->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[5]=$tipoAlcance6;
       
        $manager->persist($tipoAlcance6);
        $manager->flush();                          
        
        $tipoAlcance7 = new CategoriaIncidencia();   
        $tipoAlcance7->setNombre("Falla en Ingreso de Paciente a SIGGES");
        $tipoAlcance7->setDescripcion("Falla en Ingreso de Paciente a SIGGES");                
        $tipoAlcance7->setComponente($componente1);
        $tipoAlcance7->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[6]=$tipoAlcance7;
        
        $manager->persist($tipoAlcance7);
        $manager->flush();                                  

        $tipoAlcance8 = new CategoriaIncidencia();   
        $tipoAlcance8->setNombre("Ingresar Paciente");
        $tipoAlcance8->setDescripcion("Ingresar Paciente");                
        $tipoAlcance8->setComponente($componente1);
        $tipoAlcance8->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[7]=$tipoAlcance8;
        
        $manager->persist($tipoAlcance8);
        $manager->flush();                          
        
        $tipoAlcance9 = new CategoriaIncidencia();   
        $tipoAlcance9->setNombre("Llamada Grupo Resolutor");
        $tipoAlcance9->setDescripcion("Llamada Grupo Resolutor");                
        $tipoAlcance9->setComponente($componente1);
        $tipoAlcance9->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[8]=$tipoAlcance9;
        
        $manager->persist($tipoAlcance9);
        $manager->flush();                                          
        
        $tipoAlcance10 = new CategoriaIncidencia();   
        $tipoAlcance10->setNombre("Paciente Bloqueado");
        $tipoAlcance10->setDescripcion("Paciente Bloqueado");                
        $tipoAlcance10->setComponente($componente1);
        $tipoAlcance10->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[9]=$tipoAlcance10;
        
        $manager->persist($tipoAlcance10);
        $manager->flush();        
        
        $tipoAlcance11 = new CategoriaIncidencia();   
        $tipoAlcance11->setNombre("Recalculo");
        $tipoAlcance11->setDescripcion("Recalculo");                
        $tipoAlcance11->setComponente($componente1);
        $tipoAlcance11->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[10]=$tipoAlcance11;
        
        $manager->persist($tipoAlcance11);
        $manager->flush();      
        
        $tipoAlcance12 = new CategoriaIncidencia();   
        $tipoAlcance12->setNombre("Apoyo Usuario");
        $tipoAlcance12->setDescripcion("Apoyo Usuario");                
        $tipoAlcance12->setComponente($componente1);
        $tipoAlcance12->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[11]=$tipoAlcance12;
        
        $manager->persist($tipoAlcance12);
        $manager->flush();           
        
        $tipoAlcance13 = new CategoriaIncidencia();   
        $tipoAlcance13->setNombre("Eliminacion de Paciente");
        $tipoAlcance13->setDescripcion("Eliminacion de Paciente");                
        $tipoAlcance13->setComponente($componente1);
        $tipoAlcance13->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[12]=$tipoAlcance13;
        
        $manager->persist($tipoAlcance13);
        $manager->flush();       
        
        $tipoAlcance14 = new CategoriaIncidencia();   
        $tipoAlcance14->setNombre("Modificacion de datos");
        $tipoAlcance14->setDescripcion("Modificacion de datos");                
        $tipoAlcance14->setComponente($componente1);
        $tipoAlcance14->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[13]=$tipoAlcance14;
        
        $manager->persist($tipoAlcance14);
        $manager->flush();              
        
        $tipoAlcance15 = new CategoriaIncidencia();   
        $tipoAlcance15->setNombre("Configuración del Browser");
        $tipoAlcance15->setDescripcion("Configuración del Browser");                
        $tipoAlcance15->setComponente($componente1);
        $tipoAlcance15->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[14]=$tipoAlcance15;
        
        $manager->persist($tipoAlcance15);
        $manager->flush();          
        
        $tipoAlcance16 = new CategoriaIncidencia();   
        $tipoAlcance16->setNombre("Eliminación de documentos fuera de plazo");
        $tipoAlcance16->setDescripcion("Eliminación de documentos fuera de plazo");                
        $tipoAlcance16->setComponente($componente1);
        $tipoAlcance16->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[15]=$tipoAlcance16;
        
        $manager->persist($tipoAlcance16);
        $manager->flush(); 
        
        $tipoAlcance17 = new CategoriaIncidencia();   
        $tipoAlcance17->setNombre("Actualizar Datos del beneficiario");
        $tipoAlcance17->setDescripcion("Actualizar Datos del beneficiario");                
        $tipoAlcance17->setComponente($componente1);
        $tipoAlcance17->setIdComponente($componente1->getId());
        
        $categoriasIncidenciasSigges[16]=$tipoAlcance17;
        
        $manager->persist($tipoAlcance17);
        $manager->flush();         
        
        //-------------------INCIDENCIAS GGPF-----------------------
        
        $categoriasIncidenciasGgpf= array();
        
        $tipoAlcance18 = new CategoriaIncidencia();   
        $tipoAlcance18->setNombre("Error/ Falla Acceso");
        $tipoAlcance18->setDescripcion("Error/ Falla Acceso");                
        $tipoAlcance18->setComponente($componente2);
        $tipoAlcance18->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[0]=$tipoAlcance18;
        
        $manager->persist($tipoAlcance18);
        $manager->flush();  
        
        $tipoAlcance19 = new CategoriaIncidencia();   
        $tipoAlcance19->setNombre("Crear Acceso");
        $tipoAlcance19->setDescripcion("Crear Acceso");                
        $tipoAlcance19->setComponente($componente2);
        $tipoAlcance19->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[1]=$tipoAlcance19;
        
        $manager->persist($tipoAlcance19);
        $manager->flush();          
        
        $tipoAlcance20 = new CategoriaIncidencia();   
        $tipoAlcance20->setNombre("Solicitud Clave");
        $tipoAlcance20->setDescripcion("Solicitud Clave");                
        $tipoAlcance20->setComponente($componente2);
        $tipoAlcance20->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[2]=$tipoAlcance20;
        
        $manager->persist($tipoAlcance20);
        $manager->flush();         
        
        $tipoAlcance21 = new CategoriaIncidencia();   
        $tipoAlcance21->setNombre("Anular Folio");
        $tipoAlcance21->setDescripcion("Anular Folio");                
        $tipoAlcance21->setComponente($componente2);
        $tipoAlcance21->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[3]=$tipoAlcance21;
        
        $manager->persist($tipoAlcance21);
        $manager->flush();   
        
        $tipoAlcance22 = new CategoriaIncidencia();   
        $tipoAlcance22->setNombre("Cuenta de usuario bloqueada");
        $tipoAlcance22->setDescripcion("Cuenta de usuario bloqueada");                
        $tipoAlcance22->setComponente($componente2);
        $tipoAlcance22->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[4]=$tipoAlcance22;
        
        $manager->persist($tipoAlcance22);
        $manager->flush();         
                
        $tipoAlcance23 = new CategoriaIncidencia();   
        $tipoAlcance23->setNombre("Desbloquer cuenta de usuario");
        $tipoAlcance23->setDescripcion("Desbloquer cuenta de usuario");                
        $tipoAlcance23->setComponente($componente2);
        $tipoAlcance23->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[5]=$tipoAlcance23;
        
        $manager->persist($tipoAlcance23);
        $manager->flush();   
        
        $tipoAlcance24 = new CategoriaIncidencia();   
        $tipoAlcance24->setNombre("Beneficiario no puede pagar por Cuenta Cerrada");
        $tipoAlcance24->setDescripcion("Beneficiario no puede pagar por Cuenta Cerrada");                
        $tipoAlcance24->setComponente($componente2);
        $tipoAlcance24->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[6]=$tipoAlcance24;
        
        $manager->persist($tipoAlcance24);
        $manager->flush();           
        
        $tipoAlcance25 = new CategoriaIncidencia();   
        $tipoAlcance25->setNombre("Abrir cuenta de titular");
        $tipoAlcance25->setDescripcion("Abrir cuenta de titular");                
        $tipoAlcance25->setComponente($componente2);
        $tipoAlcance25->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[7]=$tipoAlcance25;
        
        $manager->persist($tipoAlcance25);
        $manager->flush(); 
        
        $tipoAlcance26 = new CategoriaIncidencia();   
        $tipoAlcance26->setNombre("Sistema no muestra deuda");
        $tipoAlcance26->setDescripcion("Sistema no muestra deuda");                
        $tipoAlcance26->setComponente($componente2);
        $tipoAlcance26->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[8]=$tipoAlcance26;
        
        $manager->persist($tipoAlcance26);
        $manager->flush();      
        
        $tipoAlcance27 = new CategoriaIncidencia();   
        $tipoAlcance27->setNombre("Sistema no registra el pago");
        $tipoAlcance27->setDescripcion("Sistema no registra el pago");                
        $tipoAlcance27->setComponente($componente2);
        $tipoAlcance27->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[9]=$tipoAlcance27;
        
        $manager->persist($tipoAlcance27);
        $manager->flush();      
        
        $tipoAlcance28 = new CategoriaIncidencia();   
        $tipoAlcance28->setNombre("Sistema no permite condonar GES");
        $tipoAlcance28->setDescripcion("Sistema no permite condonar GES");                
        $tipoAlcance28->setComponente($componente2);
        $tipoAlcance28->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[10]=$tipoAlcance28;
        
        $manager->persist($tipoAlcance28);
        $manager->flush();  

        $tipoAlcance29 = new CategoriaIncidencia();   
        $tipoAlcance29->setNombre("Sistema no permite condonar MAI NO GES");
        $tipoAlcance29->setDescripcion("Sistema no permite condonar MAI NO GES");                
        $tipoAlcance29->setComponente($componente2);
        $tipoAlcance29->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[11]=$tipoAlcance29;
        
        $manager->persist($tipoAlcance29);
        $manager->flush();     
        
        $tipoAlcance30 = new CategoriaIncidencia();   
        $tipoAlcance30->setNombre("No se muestra la condonación GES");
        $tipoAlcance30->setDescripcion("No se muestra la condonación GES");                
        $tipoAlcance30->setComponente($componente2);
        $tipoAlcance30->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[12]=$tipoAlcance30;
        
        $manager->persist($tipoAlcance30);
        $manager->flush();  
        
        $tipoAlcance31 = new CategoriaIncidencia();   
        $tipoAlcance31->setNombre("No se muestra la condonación MAI NO GES");
        $tipoAlcance31->setDescripcion("No se muestra la condonación MAI NO GES");                
        $tipoAlcance31->setComponente($componente2);
        $tipoAlcance31->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[13]=$tipoAlcance31;
        
        $manager->persist($tipoAlcance31);
        $manager->flush();            
        
        $tipoAlcance32 = new CategoriaIncidencia();   
        $tipoAlcance32->setNombre("Sistema no muestra la prestación");
        $tipoAlcance32->setDescripcion("Sistema no muestra la prestación");                
        $tipoAlcance32->setComponente($componente2);
        $tipoAlcance32->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[14]=$tipoAlcance32;
        
        $manager->persist($tipoAlcance32);
        $manager->flush();   
        
        $tipoAlcance33 = new CategoriaIncidencia();   
        $tipoAlcance33->setNombre("Folio congelado");
        $tipoAlcance33->setDescripcion("Folio congelado");                
        $tipoAlcance33->setComponente($componente2);
        $tipoAlcance33->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[15]=$tipoAlcance33;
        
        $manager->persist($tipoAlcance33);
        $manager->flush();     
        
        $tipoAlcance34 = new CategoriaIncidencia();   
        $tipoAlcance34->setNombre("Descongelar Folio");
        $tipoAlcance34->setDescripcion("Descongelar Folio");                
        $tipoAlcance34->setComponente($componente2);
        $tipoAlcance34->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[16]=$tipoAlcance34;
        
        $manager->persist($tipoAlcance34);
        $manager->flush();  
        
        $tipoAlcance35 = new CategoriaIncidencia();   
        $tipoAlcance35->setNombre("Sistema despliega venta con error");
        $tipoAlcance35->setDescripcion("Sistema despliega venta con error");                
        $tipoAlcance35->setComponente($componente2);
        $tipoAlcance35->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[17]=$tipoAlcance35;
        
        $manager->persist($tipoAlcance35);
        $manager->flush(); 

        $tipoAlcance36 = new CategoriaIncidencia();   
        $tipoAlcance36->setNombre("No permite ingresar valores en el campo");
        $tipoAlcance36->setDescripcion("No permite ingresar valores en el campo");                
        $tipoAlcance36->setComponente($componente2);
        $tipoAlcance36->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[18]=$tipoAlcance36;
        
        $manager->persist($tipoAlcance36);
        $manager->flush();        
        
        $tipoAlcance37 = new CategoriaIncidencia();   
        $tipoAlcance37->setNombre("No despliega valores en la ventana");
        $tipoAlcance37->setDescripcion("No despliega valores en la ventana");                
        $tipoAlcance37->setComponente($componente2);
        $tipoAlcance37->setIdComponente($componente2->getId());
        
        $categoriasIncidenciasGgpf[19]=$tipoAlcance37;
        
        $manager->persist($tipoAlcance37);
        $manager->flush();   
        
        //------------------------------Incidencias PM------------------------------
        
        $categoriasIncidenciasPm[]=array();
        
        $tipoAlcance38 = new CategoriaIncidencia();   
        $tipoAlcance38->setNombre("No despliega valores en la ventana");
        $tipoAlcance38->setDescripcion("No despliega valores en la ventana");                
        $tipoAlcance38->setComponente($componente3);
        $tipoAlcance38->setIdComponente($componente3->getId());
        
        $categoriasIncidenciasPm[0]=$tipoAlcance38;
        
        $manager->persist($tipoAlcance38);
        $manager->flush();   
        
        $tipoAlcance39 = new CategoriaIncidencia();   
        $tipoAlcance39->setNombre("Crear Acceso");
        $tipoAlcance39->setDescripcion("Crear Acceso");                
        $tipoAlcance39->setComponente($componente3);
        $tipoAlcance39->setIdComponente($componente3->getId());
        
        $categoriasIncidenciasPm[1]=$tipoAlcance39;
        
        $manager->persist($tipoAlcance39);
        $manager->flush();  
        
        $tipoAlcance40 = new CategoriaIncidencia();   
        $tipoAlcance40->setNombre("Solicitud Clave");
        $tipoAlcance40->setDescripcion("Solicitud Clave");                
        $tipoAlcance40->setComponente($componente3);
        $tipoAlcance40->setIdComponente($componente3->getId());
        
        $categoriasIncidenciasPm[2]=$tipoAlcance40;
        
        $manager->persist($tipoAlcance40);
        $manager->flush();         
        
        $tipoAlcance41 = new CategoriaIncidencia();   
        $tipoAlcance41->setNombre("No se puede imprimir");
        $tipoAlcance41->setDescripcion("No se puede imprimir");                
        $tipoAlcance41->setComponente($componente3);
        $tipoAlcance41->setIdComponente($componente3->getId());
        
        $categoriasIncidenciasPm[3]=$tipoAlcance41;
        
        $manager->persist($tipoAlcance41);
        $manager->flush();         
        
        $tipoAlcance42 = new CategoriaIncidencia();   
        $tipoAlcance42->setNombre("Modificacion Prestamo Medico");
        $tipoAlcance42->setDescripcion("Modificacion Prestamo Medico");                
        $tipoAlcance42->setComponente($componente3);
        $tipoAlcance42->setIdComponente($componente3->getId());
        
        $categoriasIncidenciasPm[4]=$tipoAlcance42;
        
        $manager->persist($tipoAlcance42);
        $manager->flush();          
        
        //-----------------Incidencias BGI / DataWareHouse--------------------------
        
        $categoriasIncidenciasDwh[]=array();
        
        $tipoAlcance43 = new CategoriaIncidencia();   
        $tipoAlcance43->setNombre("Error/ Falla Acceso");
        $tipoAlcance43->setDescripcion("Error/ Falla Acceso");                
        $tipoAlcance43->setComponente($componente4);
        $tipoAlcance43->setIdComponente($componente4->getId());
        
        $categoriasIncidenciasDwh[0]=$tipoAlcance43;
        
        $manager->persist($tipoAlcance43);
        $manager->flush();          
        
        $tipoAlcance44 = new CategoriaIncidencia();   
        $tipoAlcance44->setNombre("Crear Acceso");
        $tipoAlcance44->setDescripcion("Crear Acceso");                
        $tipoAlcance44->setComponente($componente4);
        $tipoAlcance44->setIdComponente($componente4->getId());
        
        $categoriasIncidenciasDwh[1]=$tipoAlcance44;
        
        $manager->persist($tipoAlcance44);
        $manager->flush();   
        
        $tipoAlcance45 = new CategoriaIncidencia();   
        $tipoAlcance45->setNombre("Solicitud Clave");
        $tipoAlcance45->setDescripcion("Solicitud Clave");                
        $tipoAlcance45->setComponente($componente4);
        $tipoAlcance45->setIdComponente($componente4->getId());
        
        $categoriasIncidenciasDwh[2]=$tipoAlcance45;
        
        $manager->persist($tipoAlcance45);
        $manager->flush();     
        
        $tipoAlcance46 = new CategoriaIncidencia();   
        $tipoAlcance46->setNombre("Extractores/procesos especiales");
        $tipoAlcance46->setDescripcion("Extractores/procesos especiales");                
        $tipoAlcance46->setComponente($componente4);
        $tipoAlcance46->setIdComponente($componente4->getId());
        
        $categoriasIncidenciasDwh[3]=$tipoAlcance46;
        
        $manager->persist($tipoAlcance46);
        $manager->flush();  

        $tipoAlcance47 = new CategoriaIncidencia();   
        $tipoAlcance47->setNombre("Soporte creación/mantención de consultas");
        $tipoAlcance47->setDescripcion("Soporte creación/mantención de consultas");                
        $tipoAlcance47->setComponente($componente4);
        $tipoAlcance47->setIdComponente($componente4->getId());
        
        $categoriasIncidenciasDwh[4]=$tipoAlcance47;
        
        $manager->persist($tipoAlcance47);
        $manager->flush();        
        
        $tipoAlcance48 = new CategoriaIncidencia();   
        $tipoAlcance48->setNombre("Cuadratura/Análisis de Informes");
        $tipoAlcance48->setDescripcion("Cuadratura/Análisis de Informes");                
        $tipoAlcance48->setComponente($componente4);
        $tipoAlcance48->setIdComponente($componente4->getId());
        
        $categoriasIncidenciasDwh[5]=$tipoAlcance48;
        
        $manager->persist($tipoAlcance48);
        $manager->flush();       
        
        $tipoAlcance49 = new CategoriaIncidencia();   
        $tipoAlcance49->setNombre("Soporte uso de Discoverer/herramientas");
        $tipoAlcance49->setDescripcion("Soporte uso de Discoverer/herramientas");                
        $tipoAlcance49->setComponente($componente4);
        $tipoAlcance49->setIdComponente($componente4->getId());
        
        $categoriasIncidenciasDwh[6]=$tipoAlcance49;
        
        $manager->persist($tipoAlcance49);
        $manager->flush();    
        
        $tipoAlcance50 = new CategoriaIncidencia();   
        $tipoAlcance50->setNombre("Soporte Ejecución Informes/Consultas");
        $tipoAlcance50->setDescripcion("Soporte Ejecución Informes/Consultas");                
        $tipoAlcance50->setComponente($componente4);
        $tipoAlcance50->setIdComponente($componente4->getId());
        
        $categoriasIncidenciasDwh[7]=$tipoAlcance50;
        
        $manager->persist($tipoAlcance50);
        $manager->flush();   
        
        $tipoAlcance51 = new CategoriaIncidencia();   
        $tipoAlcance51->setNombre("Corrección de datos");
        $tipoAlcance51->setDescripcion("Corrección de datos");                
        $tipoAlcance51->setComponente($componente4);
        $tipoAlcance51->setIdComponente($componente4->getId());
        
        $categoriasIncidenciasDwh[8]=$tipoAlcance51;
        
        $manager->persist($tipoAlcance51);
        $manager->flush();   
        
        //--------------------------------------------------------------------------
        
        $tiposRequerimiento= array();
        
        $connection->exec("ALTER TABLE tipo_requerimiento AUTO_INCREMENT = 1;");                
        
        $tipoAlcance1 = new TipoRequerimiento();
        $tipoAlcance1->setNombre("Error/ Falla Acceso");
        $tipoAlcance1->setDescripcion("ACR Consulta");                
        $tipoAlcance1->setComponente($componente1);
        $tipoAlcance1->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance1);                        
        
        $manager->persist($tipoAlcance1);
        $manager->flush();                         
        
        $tipoAlcance2 = new TipoRequerimiento();   
        $tipoAlcance2->setNombre("Adm. Establecimiento");
        $tipoAlcance2->setDescripcion("Adm. Establecimiento");                
        $tipoAlcance2->setComponente($componente1);
        $tipoAlcance2->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance2);        
        
        $manager->persist($tipoAlcance2);
        $manager->flush();                           
                
        $tipoAlcance3 = new TipoRequerimiento();
        $tipoAlcance3->setNombre("Adm. Colas");
        $tipoAlcance3->setDescripcion("Adm. Colas");          
        $tipoAlcance3->setComponente($componente1);
        $tipoAlcance3->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance3);        
        
        $manager->persist($tipoAlcance3);
        $manager->flush();                 
                
        $tipoAlcance4 = new TipoRequerimiento();
        $tipoAlcance4->setNombre("Arancel");
        $tipoAlcance4->setDescripcion("Arancel");                
        $tipoAlcance4->setComponente($componente1);
        $tipoAlcance4->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance4);        
        
        $manager->persist($tipoAlcance4);
        $manager->flush();              
                
        $tipoAlcance5 = new TipoRequerimiento();
        $tipoAlcance5->setNombre("Beneficiario");
        $tipoAlcance5->setDescripcion("Beneficiario");        
        $tipoAlcance5->setComponente($componente1);
        $tipoAlcance5->setIdComponente($componente1->getId());        
        
        array_push($tiposRequerimiento,$tipoAlcance5);        
                
        
        $manager->persist($tipoAlcance5);
        $manager->flush();                   
                
        $tipoAlcance6 = new TipoRequerimiento();
        $tipoAlcance6->setNombre("Búsqueda paciente");
        $tipoAlcance6->setDescripcion("Búsqueda paciente");                
        $tipoAlcance6->setComponente($componente1);
        $tipoAlcance6->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance6);        
        
        $manager->persist($tipoAlcance6);
        $manager->flush();        
                
        $tipoAlcance7 = new TipoRequerimiento();
        $tipoAlcance7->setNombre("CAT");
        $tipoAlcance7->setDescripcion("CAT");                
        $tipoAlcance7->setComponente($componente1);
        $tipoAlcance7->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance7);        
        
        $manager->persist($tipoAlcance7);
        $manager->flush();              
                
        $tipoAlcance8 = new TipoRequerimiento();
        $tipoAlcance8->setNombre("CUP");
        $tipoAlcance8->setDescripcion("CUP");                
        $tipoAlcance8->setComponente($componente1);
        $tipoAlcance8->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance8);        
        
        $manager->persist($tipoAlcance8);
        $manager->flush();          
        
        $tipoAlcance9 = new TipoRequerimiento();
        $tipoAlcance9->setNombre("Datamart");
        $tipoAlcance9->setDescripcion("Datamart");            
        $tipoAlcance9->setComponente($componente1);
        $tipoAlcance9->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance9);        
        
        $manager->persist($tipoAlcance9);
        $manager->flush();               
                
        $tipoAlcance10 = new TipoRequerimiento();
        $tipoAlcance10->setNombre("DDE");
        $tipoAlcance10->setDescripcion("DDE");                
        $tipoAlcance10->setComponente($componente1);
        $tipoAlcance10->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance10);        
                
        
        $manager->persist($tipoAlcance10);
        $manager->flush();          
        
        $tipoAlcance11 = new TipoRequerimiento();
        $tipoAlcance11->setNombre("Desbloqueo prev. fallecido");
        $tipoAlcance11->setDescripcion("Desbloqueo prev. fallecido");                
        $tipoAlcance11->setComponente($componente1);
        $tipoAlcance11->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance11);        
        
        $manager->persist($tipoAlcance11);
        $manager->flush();                  
                
        $tipoAlcance12 = new TipoRequerimiento();
        $tipoAlcance12->setNombre("ENDECA");
        $tipoAlcance12->setDescripcion("ENDECA");             
        $tipoAlcance12->setComponente($componente1);
        $tipoAlcance12->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance12);        
        
        $manager->persist($tipoAlcance12);
        $manager->flush();                  
                
        $tipoAlcance13 = new TipoRequerimiento();
        $tipoAlcance13->setNombre("Extracción");
        $tipoAlcance13->setDescripcion("Extracción");         
        $tipoAlcance13->setComponente($componente1);
        $tipoAlcance13->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance13);        
        
        $manager->persist($tipoAlcance13);
        $manager->flush();                     
                
        $tipoAlcance14 = new TipoRequerimiento();
        $tipoAlcance14->setNombre("EXTRAEGGPF");
        $tipoAlcance14->setDescripcion("EXTRAEGGPF");         
        $tipoAlcance14->setComponente($componente1);
        $tipoAlcance14->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance14);        
        
        $manager->persist($tipoAlcance14);
        $manager->flush();                         
                
        $tipoAlcance15 = new TipoRequerimiento();
        $tipoAlcance15->setNombre("Facturación");
        $tipoAlcance15->setDescripcion("Facturación");        
        $tipoAlcance15->setComponente($componente1);
        $tipoAlcance15->setIdComponente($componente1->getId());        
        
        array_push($tiposRequerimiento,$tipoAlcance15);        
        
        $manager->persist($tipoAlcance15);
        $manager->flush();                  
                
        $tipoAlcance16 = new TipoRequerimiento();
        $tipoAlcance16->setNombre("Parametrización eventos GO");
        $tipoAlcance16->setDescripcion("Parametrización eventos GO");                
        $tipoAlcance16->setComponente($componente1);
        $tipoAlcance16->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance16);        
        
        $manager->persist($tipoAlcance16);
        $manager->flush();                  
                
        $tipoAlcance17 = new TipoRequerimiento();
        $tipoAlcance17->setNombre("IFL");
        $tipoAlcance17->setDescripcion("IFL");                
        $tipoAlcance17->setComponente($componente1);
        $tipoAlcance17->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance17);        
        
        $manager->persist($tipoAlcance17);
        $manager->flush();                 
                
        $tipoAlcance18 = new TipoRequerimiento();
        $tipoAlcance18->setNombre("Manuales");
        $tipoAlcance18->setDescripcion("Manuales");           
        $tipoAlcance18->setComponente($componente1);
        $tipoAlcance18->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance18);        
        
        $manager->persist($tipoAlcance18);
        $manager->flush();                         
                
        $tipoAlcance19 = new TipoRequerimiento();
        $tipoAlcance19->setNombre("Monitoreo y consultas");
        $tipoAlcance19->setDescripcion("Monitoreo y consultas");                
        $tipoAlcance19->setComponente($componente1);
        $tipoAlcance19->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance19);        
        
        $manager->persist($tipoAlcance19);
        $manager->flush();                       
                
        $tipoAlcance20 = new TipoRequerimiento();
        $tipoAlcance20->setNombre("Reporte OFF-Line");
        $tipoAlcance20->setDescripcion("Reporte OFF-Line");   
        $tipoAlcance20->setComponente($componente1);
        $tipoAlcance20->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance20);        
        
        $manager->persist($tipoAlcance20);
        $manager->flush();                                               
                
        $tipoAlcance21 = new TipoRequerimiento();
        $tipoAlcance21->setNombre("RNP");
        $tipoAlcance21->setDescripcion("RNP");                
        $tipoAlcance21->setComponente($componente1);
        $tipoAlcance21->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance21);        
        
        $manager->persist($tipoAlcance21);
        $manager->flush();                               
                
        $tipoAlcance22 = new TipoRequerimiento();    
        $tipoAlcance22->setNombre("POII-POIM");
        $tipoAlcance22->setDescripcion("POII-POIM");          
        $tipoAlcance22->setComponente($componente1);
        $tipoAlcance22->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance22);        
        
        $manager->persist($tipoAlcance22);
        $manager->flush();          
                
        $tipoAlcance23 = new TipoRequerimiento();
        $tipoAlcance23->setNombre("Recálculo GO");
        $tipoAlcance23->setDescripcion("Recálculo GO");       
        $tipoAlcance23->setComponente($componente1);
        $tipoAlcance23->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance23);        
        
        $manager->persist($tipoAlcance23);
        $manager->flush();                  
                
        $tipoAlcance24 = new TipoRequerimiento();
        $tipoAlcance24->setNombre("Revalorizar");
        $tipoAlcance24->setDescripcion("Revalorizar");        
        $tipoAlcance24->setComponente($componente1);
        $tipoAlcance24->setIdComponente($componente1->getId());        
        
        array_push($tiposRequerimiento,$tipoAlcance24);        
        
        $manager->persist($tipoAlcance24);
        $manager->flush();                          
                
        $tipoAlcance25 = new TipoRequerimiento();
        $tipoAlcance25->setNombre("Proceso CAC");
        $tipoAlcance25->setDescripcion("Proceso CAC");                
        $tipoAlcance25->setComponente($componente1);
        $tipoAlcance25->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance25);        
        
        $manager->persist($tipoAlcance25);
        $manager->flush();                          
                
        $tipoAlcance26 = new TipoRequerimiento();
        $tipoAlcance26->setNombre("VIH");
        $tipoAlcance26->setDescripcion("VIH");                
        $tipoAlcance26->setComponente($componente1);
        $tipoAlcance26->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance26);        
        
        $manager->persist($tipoAlcance26);
        $manager->flush();                          
                
        $tipoAlcance27 = new TipoRequerimiento();
        $tipoAlcance27->setNombre("WS Certificador Prev.");
        $tipoAlcance27->setDescripcion("WS Certificador Prev.");                
        $tipoAlcance27->setComponente($componente1);
        $tipoAlcance27->setIdComponente($componente1->getId());
        
        array_push($tiposRequerimiento,$tipoAlcance27);        
        
        $manager->persist($tipoAlcance27);
        $manager->flush();                    
        
        //------------------------------POBLAR INCIDENCIAS-----------------------------------------------
        /*
        $descripcionesIncidencia=[
            'Crear a un nuevo Medico en sistema',
            'Ticket con problemas enfocados en el cumplimientos de garantías',
            'Ticket con problemas  de garantías',
            'Requerimiento de  Gestión de Usuario (FONASA, MINSAL) (crear/eliminar usuarios, asignar/eliminar perfiles)',
            'Requerimientos de eliminar documentos ingresados',
            'Se registran prestaciones que quedan inválidas dentro del proceso',
            'Problemas de  casos creados',
            'Anular Folio de Cobranzas',
            'Beneficiario FONASA no puede pagar, dice cuenta bloqueada',
            'Se bloqueo mi cuenta de acceso',
            'Beneficiario FONASA no puede pagar, dice cuenta cerrada',
            'Solicita abrir cuenta de titular',
            'Beneficiario desea pagar y el sistema no muestra la deuda',
            'Beneficiario pago deudr y el sistema no muestra el pago',
            'No podemos condonar una deuda',
            'A consultar cuenta el folio se encuentra congelado',
            'No permite ingresar valores en el campo',
            'Solicito crear nuevo usuario MINSAL',
            'Se necesita generar una nueva extracción de datos ',
            'Se necesita revisar una cuadratura de datos',
            'Soporte uso de Discoverer/herramientas',
            'Solicito crear nuevo usuario',
            'No puedo imprimir desde el sistema'
        ];
        
        $componentes=[$componente1,$componente2,$componente3,$componente4];
        $estadosIncidencia=[$estadoIncidencia2,$estadoIncidencia3,$estadoIncidencia4];        
        $severidades=[$severidad1,$severidad2,$severidad3];
        
        $incidencias=array();
                        
        $connection->exec("ALTER TABLE incidencia AUTO_INCREMENT = 1;");       
            
        for($i=0;$i<100;$i++){
            srand(make_seed());
            
            $incidencia= new Incidencia();
            $incidencia->setNumeroTicket(mt_rand()%1000+10000);
            $incidencia->setDescripcion($descripcionesIncidencia[mt_rand()%sizeof($descripcionesIncidencia)]);            
            $incidencia->setFechaReporte(randomDate(new\DateTime('2016-01-01'),new\DateTime('2016-04-01')));
            $incidencia->setFechaIngreso(randomDate(new\DateTime('2016-04-02'),new\DateTime('2016-04-10')));
            $fechaInicio=randomDate(new\DateTime('2016-04-11'),new\DateTime('2016-04-20'));
            $incidencia->setFechaInicio($fechaInicio);
            $estadoIncidencia=$estadosIncidencia[mt_rand()%2];
            $incidencia->setEstadoIncidencia($estadoIncidencia);
            $incidencia->setIdEstadoIncidencia($estadoIncidencia->getId());
            $severidad=$severidades[mt_rand()%3];
            $incidencia->setSeveridad($severidad);
            $incidencia->setIdSeveridad($severidad->getId());
            
            if(mt_rand()%2){
                $incidencia->setFechaSalida(randomDate(new\DateTime('2016-04-21'),new\DateTime('2016-04-27')));
                $incidencia->setEstadoIncidencia($estadoIncidencia4);
                $incidencia->setIdEstadoIncidencia($estadoIncidencia4->getId());
            }
            $incidencia->setFechaUltHh($fechaInicio);
            $incidencia->setHhEfectivas(mt_rand()%4);
                        
            $componente=$componentes[mt_rand()%4];
            
            $incidencia->setComponente($componente);
            $incidencia->setIdComponente($componente->getId());
            
            switch($componente->getNombre()){                
                case 'SIGGES':
                    $rand=mt_rand()%sizeof($categoriasIncidenciasSigges);                    
                    $categoriaIncidencia=$categoriasIncidenciasSigges[$rand];                    
                    $incidencia->setCategoriaIncidencia($categoriaIncidencia);
                    $incidencia->setIdCategoriaIncidencia($categoriaIncidencia->getId());
                    break;
                case 'GGPF':
                    $rand=mt_rand()%sizeof($categoriasIncidenciasGgpf);                    
                    $categoriaIncidencia=$categoriasIncidenciasGgpf[$rand];
                    $incidencia->setCategoriaIncidencia($categoriaIncidencia);
                    $incidencia->setIdCategoriaIncidencia($categoriaIncidencia->getId());
                    break;
                case 'PM':
                    $rand=mt_rand()%sizeof($categoriasIncidenciasPm);                    
                    $categoriaIncidencia=$categoriasIncidenciasPm[$rand];
                    $incidencia->setCategoriaIncidencia($categoriaIncidencia);
                    $incidencia->setIdCategoriaIncidencia($categoriaIncidencia->getId());
                    break;
                case 'BGI / DataWareHouse':                    
                    $rand=mt_rand()%sizeof($categoriasIncidenciasDwh);                    
                    $categoriaIncidencia=$categoriasIncidenciasDwh[$rand];
                    $incidencia->setCategoriaIncidencia($categoriaIncidencia);
                    $incidencia->setIdCategoriaIncidencia($categoriaIncidencia->getId());
                    break;                
            }
            
            $manager->persist($incidencia);
            $manager->flush();    
            
            array_push($incidencias,$incidencia);            
        }
        
        
        $estadosMantencion=[$estadoMantencion1 ,$estadoMantencion2, $estadoMantencion3, $estadoMantencion6];        
        
        $connection->exec("ALTER TABLE mantencion AUTO_INCREMENT = 1;");       
            
        for($i=0;$i<50;$i++){
            srand(make_seed());
            
            $mantencion= new Mantencion();
            
            $mantencion->setDescripcion($descripcionesIncidencia[mt_rand()%sizeof($descripcionesIncidencia)]);            
            $componente=$componentes[mt_rand()%4];
            
            $mantencion->setComponente($componente);
            $mantencion->setIdComponente($componente->getId());            
            $mantencion->setCodigoInterno(mt_rand()%100000);
            
            $estadoMantencion=$estadosMantencion[mt_rand()%3];
            $mantencion->setEstadoMantencion($estadoMantencion);
            $mantencion->setIdEstadoMantencion($estadoMantencion->getId());                        
            
            $mantencion->setFechaIngreso(randomDate(new\DateTime('2016-04-02'),new\DateTime('2016-04-10')));
            $fechaInicio=randomDate(new\DateTime('2016-04-11'),new\DateTime('2016-04-20'));
            $mantencion->setFechaInicio($fechaInicio);
            
            $mantencion->setFechaUltHh($fechaInicio);
            
            $mantencion->setInicioProgramado(0);
            $mantencion->setHhEstimadas(mt_rand()%60+1);
            $mantencion->setHhEfectivas(mt_rand()%60+1);
            
            if(mt_rand()%2){//Esta cerrada
                $mantencion->setFechaSalida(randomDate(new\DateTime('2016-04-21'),new\DateTime('2016-04-27')));
                $mantencion->setEstadoMantencion($estadoMantencion6);
                $mantencion->setIdEstadoMantencion($estadoMantencion6->getId());
            }
            
            if(mt_rand()%2){ // Es evolutiva
                $mantencion->setTipoMantencion($tipo2);
                $mantencion->setIdTipoMantencion($tipo2->getId());
            }
            else{ // Es correctiva
                $mantencion->setTipoMantencion($tipo3);
                $mantencion->setIdTipoMantencion($tipo3->getId());
            }
                            
            if(mt_rand()%2){ // Es incidencia
                $mantencion->setOrigenMantencion($origen1);                
                $incidencia=array_pop($incidencias);
                $mantencion->setIncidencia($incidencia);
                $mantencion->setIdIncidencia($incidencia->getId());                                
            }
            else{ // Es requerimiento
                $mantencion->setOrigenMantencion($origen2);
                $rand=mt_rand()%sizeof($tiposRequerimiento);                
                $tipoRequerimiento=$tiposRequerimiento[$rand];
                $mantencion->setTipoRequerimiento($tipoRequerimiento);
                $mantencion->setIdTipoRequerimiento($tipoRequerimiento->getId());                                
                $mantencion->setNumeroRequerimiento(mt_rand()%10000);
            }
                                                                                                
            $manager->persist($mantencion);
            $manager->flush();    
        }
        */
    }        
}

// Find a randomDate between $start_date and $end_date
function randomDate($start_date, $end_date)
{
    // Convert to timetamps
    $min = strtotime($start_date->format('Y-m-d H:i:s'));
    $max = strtotime($end_date->format('Y-m-d H:i:s'));

    // Generate random number using above bounds
    $val = rand($min, $max);        

    // Convert back to desired date format
    return new\DateTime(date('Y-m-d H:i:s', $val));
}

// semilla de microsegundos
function make_seed()
{
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000);
}   
