<?php

namespace Fonasa\MonitorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
         //Obtener parámetros de filtros
        //$anio= $request->query->get('anio');
        //$mes= $request->query->get('mes');
        //$estado= $request->query->get('estado');        
        //////////////////       
        $fecha=new\DateTime('now');
        
        $em = $this->getDoctrine()->getManager();                
        
        ///////////// 1er CHART: INCIDENCIAS POR COMPONENTE/////////////////
        
        // Obtener categorias
        $componentes = $em->getRepository('MonitorBundle:Componente')
        ->createQueryBuilder('c')                                        
        ->orderBy('c.nombre')
        ->getQuery()
        ->getResult();   
                        
        
        // Obtener stacks
        $estados = $em->getRepository('MonitorBundle:EstadoIncidencia')
        ->createQueryBuilder('e')                                
        ->where('e.nombre in (?1)')
        ->setParameter(1, ['En Gestión FONASA','Pendiente MT','Resuelta MT'])
        ->getQuery()
        ->getResult();                   
        
        // Obtener data
        $parameters = array();                

        $qb = $em->getRepository('MonitorBundle:Incidencia')                
                ->createQueryBuilder('i')                
                ->select('c.nombre as componente, e.nombre estado, count(i.id) as cantidad')                                
                ->join('i.estadoIncidencia', 'e')
                ->join('i.componente', 'c')
                ->join('i.severidad', 'p')
                //->join('i.origen', 'o')                
                ->where('e.nombre in (?1)');                
        
        $qb->groupBy('c.nombre','e.nombre');                
                
        $parameters[1] = ['En Gestión FONASA','Pendiente MT','Resuelta MT'];
        
        $incidencias= $qb->setParameters($parameters)
                    ->getQuery()    
                    ->getResult();                   
                
        
        $incidencias_= array();
        
        foreach($incidencias as $incidencia)
            $incidencias_[$incidencia['estado']][$incidencia['componente']]=$incidencia['cantidad'];                                                       
        
        // Preparar respuesta para HighCharts 
        $series1 = array();                       
        
        foreach($estados as $estado){        
            $data_ = array();            
            $categories1 = array();
            foreach($componentes as $componente){     
                array_push($categories1, $componente->getNombre());
                if(array_key_exists($estado->getNombre(), $incidencias_)){
                    $incidencias__=$incidencias_[$estado->getNombre()];
                    if(array_key_exists($componente->getNombre(), $incidencias__))                            
                        array_push($data_,intval($incidencias__[$componente->getNombre()]));                        
                    else
                        array_push($data_, 0);
                }
                else 
                    array_push($data_, 0);                                    
            }
            $series1[]=array('name' => $estado->getNombre(), 'data' => $data_);
        }                        
        
         ///////////// 2° CHART: INCIDENCIAS POR ESTADO/////////////////
        
        //Obtener Data
        
        //Obtener total incidencias
        $qb = $em->getRepository('MonitorBundle:Incidencia')                
                ->createQueryBuilder('i')                
                ->select('count(i.id) as total')                                
                ->join('i.estadoIncidencia', 'e')
                ->join('i.componente', 'c')
                ->join('i.severidad', 's')                                              
                ->where('e.nombre in (?1)');
                
        $parameters[1] = ['En Gestión FONASA','Pendiente MT','Resuelta MT'];
        
        $totals= $qb->setParameters($parameters)
                    ->getQuery()    
                    ->getResult();            
        
        $total= $totals[0]['total'];
        
        $parameters = array();                

        $qb = $em->getRepository('MonitorBundle:Incidencia')                
                ->createQueryBuilder('i')                
                ->select('e.nombre estado, 100*(count(i.id)/?2) as porcentaje')                                
                ->join('i.estadoIncidencia', 'e')
                ->join('i.componente', 'c')
                ->join('i.severidad', 's')                                              
                ->where('e.nombre in (?1)');
                
        $qb->groupBy('e.nombre');                
                
        $parameters[1] = ['En Gestión FONASA','Pendiente MT','Resuelta MT'];
        
        $parameters[2] = $total;
                        
        $incidencias= $qb->setParameters($parameters)
                    ->getQuery()    
                    ->getResult();                   
        
        $incidencias_= array();                
        
        foreach($incidencias as $incidencia)
            $incidencias_[$incidencia['estado']]=$incidencia['porcentaje'];
        
        // Preparar respuesta para HighCharts 
        $series2 = array('name' => 'Brands', 'colorByPoint' => true);                               
        
        $data = array();
        
        foreach($estados as $estado){                    
            $data_ = array();                                 
            if(array_key_exists($estado->getNombre(), $incidencias_)){
                $incidencias__=$incidencias_[$estado->getNombre()];
                array_push($data_,$incidencias_[$estado->getNombre()]);
            }
            else {
                array_push($data_, 0);
            }                        
            $data[]=array('name' => $estado->getNombre(), 'y' => intval($data_[0]));
            
        }   
                
        $series2['data'] = $data;
                
        
        ///////////// 3er CHART: TIEMPOS DE RESOLUCIÓN DE INCIDENCIAS/////////////////
        
        $categories2 = $categories1;
        
        // Categorias
        array_unshift($categories2, 'Todos');
        
        // Obtener datos Todos
        $qb = $em->getRepository('MonitorBundle:Incidencia')                
                ->createQueryBuilder('i')                
                ->select('i.hhEfectivas as tiempo, count(i.id) as cantidad')                                
                ->join('i.estadoIncidencia', 'e')
                ->join('i.componente', 'c')
                ->join('i.severidad', 's')                                              
                ->where('e.nombre in (?1)');
                
        $qb->groupBy('i.hhEfectivas');
        
        $parameters = array();                
                
        $parameters[1] = ['Resuelta MT'];                
                        
        $incidencias= $qb->setParameters($parameters)
                    ->getQuery()    
                    ->getResult();                   
        
        $incidencias_= array();                
        
        foreach($incidencias as $incidencia)
            $incidencias_['Todos'][$incidencia['tiempo']]=$incidencia['cantidad'];
        
        // Obtener datos por componente
        $qb = $em->getRepository('MonitorBundle:Incidencia')                
                ->createQueryBuilder('i')                
                ->select('c.nombre componente, i.hhEfectivas as tiempo, count(i.id) as cantidad')                                
                ->join('i.estadoIncidencia', 'e')
                ->join('i.componente', 'c')
                ->join('i.severidad', 's')                                              
                ->where('e.nombre in (?1)');
                
        $qb->groupBy('c.nombre','i.hhEfectivas');        
        //$qb->groupBy('i.hhEfectivas');                        
        
        $parameters = array();                
                
        $parameters[1] = ['Resuelta MT'];                
                        
        $incidencias= $qb->setParameters($parameters)
                    ->getQuery()    
                    ->getResult();          
        
        foreach($incidencias as $incidencia)
            $incidencias_[$incidencia['componente']][$incidencia['tiempo']]=$incidencia['cantidad'];
        
        // Preparar respuesta para HighCharts 
        $series3 = array();                       
        
        foreach($categories2 as $categoria){        
            $data_ = array();                        
            $categories3 = array();
            for($i=0;$i<11;++$i){                 
                $categories3[] = $i.'hrs';
                if(array_key_exists($categoria, $incidencias_)){
                    $incidencias__=$incidencias_[$categoria];
                    if(array_key_exists($i, $incidencias__)){
                        array_push($data_,intval($incidencias__[$i]));  
                    }
                    else{
                        array_push($data_, 0);
                    }
                }
                else{
                    array_push($data_, 0);
                }                
            }
            $series3[]=array('name' => $categoria, 'data' => $data_);
        }                       
        
        ////////////////////// 4o CHART: HH Mantenciones////////////////////////
                
        // Obtener mantenciones del mes                 
        $qb = $em->getRepository('MonitorBundle:Mantencion')                
                ->createQueryBuilder('m')                
                ->select('tm.nombre as tipo, m.codigoInterno as nombre, m.hhEstimadas as hhEstimadas, m.hhEfectivas as hhReales')                                
                ->join('m.estadoMantencion', 'e')
                ->join('m.componente', 'c')
                //->join('m.severidad', 's')
                ->join('m.tipoMantencion', 'tm')
                ->where('tm.nombre in (?1)')                
                ->andWhere('e.nombre in (?2)');                        
                
        
        $parameters = array();
        
        $parameters[1] = ['Mantención Correctiva','Mantención Evolutiva'];                
                
        $parameters[2] = ['Cerrada'];
        
        $mantenciones= $qb->setParameters($parameters)
            ->getQuery()    
            ->getResult(); 
        
        $mantenciones_ = array();
        
        foreach($mantenciones as $mantencion){
            $tipo=$mantencion['tipo']=='Mantención Evolutiva'?'SIGG-ME':'SIGG-MC';
            $mantenciones_[$tipo.$mantencion['nombre']]=[$mantencion['hhEstimadas'],$mantencion['hhReales']];                                        
        }
                        
        // Preparar respuesta para HighCharts 
        $series4 = array();                       
        $categories4 = array_keys($mantenciones_);
        $data_ = array();                                
        $data__ = array();                                
                
        foreach($mantenciones_ as $mantencion){  
            $data_[] = $mantencion[0];
            $data__[] = $mantencion[1];                                
        }
            
        $series4[] = array('name' => 'Estimadas', 'data' => $data_);
        $series4[] = array('name' => 'Efectivas', 'data' => $data__);                                                             
        
        
        //////////////////////////// 5o Chart: Mantenciones por estado a la fecha //////////////////////////////////
        
        //Obtener Data
        // Obtener stacks
        $estados = $em->getRepository('MonitorBundle:EstadoMantencion')
        ->createQueryBuilder('e')                                
        ->where('e.nombre in (?1)')
        ->setParameter(1, ['En Desarrollo','En Testing','Cerrada'])
        ->getQuery()
        ->getResult();   
        
        //Obtener total incidencias
        $qb = $em->getRepository('MonitorBundle:Mantencion')                
                ->createQueryBuilder('m')                
                ->select('count(m.id) as total')                
                ->join('m.estadoMantencion', 'e')
                ->join('m.componente', 'c')
                //->join('m.severidad', 's')
                ->join('m.tipoMantencion', 'tm')
                ->where('tm.nombre in (?1)')                
                ->andWhere('e.nombre in (?2)');
        
        $parameters[1] = ['Mantención Correctiva','Mantención Evolutiva'];        
        $parameters[2] = ['En Desarrollo','En Testing','Cerrada'];
        
        $totals= $qb->setParameters($parameters)
                    ->getQuery()    
                    ->getResult();            
        
        $total= $totals[0]['total'];
        
        $parameters = array();                

        $qb = $em->getRepository('MonitorBundle:Mantencion')                
                ->createQueryBuilder('m')                
                ->select('e.nombre estado, count(m.id) as cantidad')                                
                ->join('m.estadoMantencion', 'e')
                ->join('m.componente', 'c')
                //->join('m.severidad', 's')
                ->join('m.tipoMantencion', 'tm')
                ->where('tm.nombre in (?1)')                
                ->andWhere('e.nombre in (?2)');
                
        $qb->groupBy('e.nombre');
        
        $parameters[1] = ['Mantención Correctiva','Mantención Evolutiva'];        
        
        $parameters[2] = ['En Desarrollo','En Testing','Cerrada'];                
                        
        $mantenciones= $qb->setParameters($parameters)
                    ->getQuery()    
                    ->getResult();                   
        
        $mantenciones_= array();
        
        foreach($mantenciones as $mantencion)
            $mantenciones_[$mantencion['estado']]=$mantencion['cantidad'];
        
        // Preparar respuesta para HighCharts 
        $series5 = array('name' => 'Brands', 'colorByPoint' => true);                               
        
        $data = array();
        
        foreach($estados as $estado){                    
            $data_ = array();                                 
            if(array_key_exists($estado->getNombre(), $mantenciones_)){
                $mantenciones__=$mantenciones_[$estado->getNombre()];
                array_push($data_,$mantenciones_[$estado->getNombre()]);
            }
            else {
                array_push($data_, 0);
            }                        
            $data[]=array('name' => $estado->getNombre(), 'y' => intval($data_[0]));            
        }   
                
        $series5['data'] = $data;
        
        //////////////////////////// 6o Chart: Mantenciones Correctivas por Componente a la fecha //////////////////////////////////
        
        // Obtener stacks
        $tiposMantencion = $em->getRepository('MonitorBundle:TipoMantencion')
        ->createQueryBuilder('t')                                
        ->where('t.nombre in (?1)')
        ->setParameter(1, ['Mantención Correctiva','Mantención Evolutiva'])
        ->getQuery()
        ->getResult();  
        
        $parameters = array();                

        $qb = $em->getRepository('MonitorBundle:Mantencion')                
                ->createQueryBuilder('m')                
                ->select('c.nombre componente, tm.nombre tipo_mantencion, count(m.id) as cantidad')                                
                ->join('m.estadoMantencion', 'e')
                ->join('m.componente', 'c')
                //->join('m.severidad', 's')
                ->join('m.tipoMantencion', 'tm')
                ->where('tm.nombre in (?1)')                
                ->andWhere('e.nombre in (?2)');
                
        $qb->groupBy('c.nombre','tm.nombre');
        
        $parameters[1] = ['Mantención Correctiva','Mantención Evolutiva'];        
        
        $parameters[2] = ['Cerrada'];                
                        
        $mantenciones= $qb->setParameters($parameters)
                    ->getQuery()    
                    ->getResult();                   
        
        $mantenciones_= array();
        
        foreach($mantenciones as $mantencion)
            $mantenciones_[$mantencion['tipo_mantencion']][$mantencion['componente']]=$mantencion['cantidad'];                                                       
        
        // Preparar respuesta para HighCharts 
        $series6 = array();                       
        
        foreach($tiposMantencion as $tipoMantencion){        
            $data_ = array();            
            $categories6 = array();
            foreach($componentes as $componente){     
                array_push($categories6, $componente->getNombre());
                if(array_key_exists($tipoMantencion->getNombre(), $mantenciones_)){
                    $mantenciones__=$mantenciones_[$tipoMantencion->getNombre()];
                    if(array_key_exists($componente->getNombre(), $mantenciones__))                            
                        array_push($data_,intval($mantenciones__[$componente->getNombre()]));                        
                    else
                        array_push($data_, 0);
                }
                else 
                    array_push($data_, 0);                                    
            }
            $series6[]=array('name' => $tipoMantencion->getNombre(), 'data' => $data_);
        }            
                                                                
        return $this->render('MonitorBundle:Dashboard:index.html.twig',
        array(
            'chartIncidenciasComponente' => array('title'      => json_encode('Incidencias por Componente: '.$fecha->format('d/m/Y')),
                                                  'yAxis'      => json_encode('Total Incidencias'),
                                                  'categories' => json_encode($categories1), 
                                                  'series'     => json_encode($series1)),
            'chartIncidenciasEstado'     => array('title'      => json_encode('Incidencias por Estado: '.ceil( date( 'j', $fecha->getTimestamp() ) / 7 ).'-'.$fecha->format('m/Y')),
                                                  //'yAxis'      => json_encode('Total Incidencias'),
                                                  //'categories' => json_encode($categories), 
                                                  'series'     => json_encode([$series2])),
            'chartHHIncidencias'         => array('title' => json_encode('Tiempos de Resolución Incidencias: '.ceil( date( 'j', $fecha->getTimestamp() ) / 7 ).'-'.$fecha->format('m/Y')),
                                                  'yAxis'      => json_encode('Cantidad de Incidencias'),
                                                  'categories' => json_encode($categories3), 
                                                  'series'     => json_encode($series3)),
            'chartHHMantenciones'        => array('title' => json_encode('HH Mantenciones: '.$fecha->format('M-Y')),
                                                  'yAxis'      => json_encode('Cantidad de HHs'),
                                                  'categories' => json_encode($categories4), 
                                                  'series'     => json_encode($series4)),
            'chartMantencionesEstado'    => array('title' => json_encode('Mantenciones por Estado a la Fecha'),
                                                   'yAxis'     => json_encode('Cantidad de Mantenciones'),
                                                   //'categories' => json_encode($categories4), 
                                                   'series'    => json_encode([$series5])),                        
            'chartMantencionesComponente' => array('title'     => json_encode('Mantenciones por Componente: '.$fecha->format('d/m/Y')),
                                                  'yAxis'      => json_encode('Total Mantenciones'),
                                                  'categories' => json_encode($categories6), 
                                                  'series'     => json_encode($series6))
        ));                                        
    }
}
