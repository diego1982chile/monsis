<?php

namespace Fonasa\MonitorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Fonasa\MonitorBundle\Entity\Mantencion;
use Fonasa\MonitorBundle\Entity\HistorialMantencion;
use Fonasa\MonitorBundle\Form\MantencionType;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Mantencion controller.
 *
 */
class MantencionController extends Controller
{
    /**
     * Lists all Mantencion entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $mantencions = $em->getRepository('MonitorBundle:Mantencion')->findAll();                

        return $this->render('MonitorBundle:mantencion:index.html.twig', array(
            'mantencions' => $mantencions,
        ));
    }

    /**
     * Creates a new Mantencion entity.
     *
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();  
        
        $idIncidencia = $request->attributes->get('idIncidencia');
        
        $mantencion = new Mantencion();
        $form = $this->createForm('Fonasa\MonitorBundle\Form\MantencionType', $mantencion, array(
                                  'idIncidencia' => $idIncidencia
        ));
        $form->handleRequest($request);                

        if ($form->isSubmitted() && $form->isValid()) {
                        
            $inicioProgramado=$request->request->get('mantencion')['inicioProgramado'];
            
            $fechaIngreso=new\DateTime('now');
            
            if($inicioProgramado){
                $nomEstado='En Cola';                            
                $msg='La mantención está en cola y tiene un inicio programado para el '.$request->request->get('incidencia')['fechaInicio'].', fecha en la que será automáticamente asignada al área de desarrollo.';
            }
            else{ //Si no es inicio programado setear la fecha de inicio
                $mantencion->setFechaInicio($fechaIngreso);
                $nomEstado='En Desarrollo';
                $msg='La mantención se ha iniciado y ha quedado asignada al área de desarrollo.';
            }
                                    
            $estado= $em->getRepository('MonitorBundle:EstadoMantencion')
                ->createQueryBuilder('e')                                
                ->where('e.nombre = ?1')
                ->setParameter(1, $nomEstado)
                ->getQuery()
                ->getResult();                        
                                    
            $mantencion->setEstadoMantencion($estado[0]);
            $mantencion->setIdEstadoMantencion($estado[0]->getId());            
            //$incidencia->setNumeroTicket($numeroTicket);
            $mantencion->setFechaIngreso($fechaIngreso);
            $mantencion->setHhEfectivas(0);
            
            switch ($mantencion->getEstadoMantencion()->getNombre()){
                case 'En Cola': // Si se deja en cola, la fecha de inicio salida se anulan
                    //$incidencia[0]->setFechaInicio(null);
                    //$incidencia[0]->setFechaSalida(null
                    $mantencion->setFechaSalida(null);
                    break;
                case 'En Desarrollo': // Si se deja en gestión FONASA, no se hace nada
                    $mantencion->setFechaSalida(null);
                    $mantencion->setFechaUltHH(new\DateTime('now'));
                    break;                            
            }
            
            //$em = $this->getDoctrine()->getManager();
            $em->persist($mantencion);
            $em->flush();

            if($idIncidencia == null){
                $this->addFlash(
                    'notice',
                    'Se ha ingresado una nueva mantención.| La mantención está asociada al requerimiento:'.$mantencion->getNumeroRequerimiento().'.|'.$msg
                );               
            }
            else{
                // Si se ha levantado una mantencion a partir de una incidencia, se debe dejar la incidencia
                // como resuelta                
                $incidencia= $em->getRepository('MonitorBundle:Incidencia')
                ->createQueryBuilder('i')                                
                ->where('i.id = ?1')                
                ->setParameter(1, $idIncidencia)                               
                ->getQuery()
                ->getResult();
                
                $estado= $em->getRepository('MonitorBundle:EstadoIncidencia')
                ->createQueryBuilder('e')                                
                ->where('e.nombre = ?1')
                ->setParameter(1, 'Resuelta MT')
                ->getQuery()
                ->getResult();                        
                
                $incidencia[0]->setEstadoIncidencia($estado[0]);
                $incidencia[0]->setIdEstadoIncidencia($estado[0]->getId());            
                $incidencia[0]->setFechaSalida(new\DateTime('now'));
                
                $em->persist($incidencia[0]);
                $em->flush();
                
                $this->addFlash(
                    'notice',
                    'Se ha ingresado una nueva mantención.| La mantención está asociada a la incidencia:'.$mantencion->getIncidencia()->getNumeroTicket().'.|'.$msg
                );
            }

            return $this->redirectToRoute('mantencion_show', array('id' => $mantencion->getId()));
        }

        //$form = $this->createForm('Fonasa\MonitorBundle\Form\MantencionType', $mantencion, array(
        //                            'idIncidencia' => $idIncidencia));        
        
        return $this->render('MonitorBundle:mantencion:new.html.twig', array(
            'mantencion' => $mantencion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Mantencion entity.
     *
     */
    public function showAction(Mantencion $mantencion)
    {
        $deleteForm = $this->createDeleteForm($mantencion);
        
        return $this->render('MonitorBundle:mantencion:show.html.twig', array(
            'mantencion' => $mantencion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Mantencion entity.
     *
     */
    public function editAction(Request $request, Mantencion $mantencion)
    {
        $deleteForm = $this->createDeleteForm($mantencion);
        $editForm = $this->createForm('Fonasa\MonitorBundle\Form\MantencionType', $mantencion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mantencion);
            $em->flush();

            return $this->redirectToRoute('mantencion_edit', array('id' => $mantencion->getId()));
        }

        return $this->render('MonitorBundle:mantencion:edit.html.twig', array(
            'mantencion' => $mantencion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Mantencion entity.
     *
     */
    public function deleteAction(Request $request, Mantencion $mantencion)
    {
        $form = $this->createDeleteForm($mantencion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($mantencion);
            $em->flush();
        }

        return $this->redirectToRoute('mantencion_index');
    }

    /**
     * Creates a form to delete a Mantencion entity.
     *
     * @param Mantencion $mantencion The Mantencion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Mantencion $mantencion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('mantencion_delete', array('id' => $mantencion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    public function checkAction(Request $request){
                
        $numeroRequerimiento= $request->request->get('numeroRequerimiento');
        $id= $request->request->get('id');
        
        $error = false;
        $message = "N°Req válido";        
        
        if (!is_numeric($numeroRequerimiento)){
            $error = true;
            $message = 'N°Req no válido';            
        }             
        
        if(!$error){
            $em = $this->getDoctrine()->getManager();                    
            //Si se esta editando o asignando un servicio se debe proveer el id del servicio
            if($id != null){                
                $incidencia= $em->getRepository('MonitorBundle:Mantencion')
                ->createQueryBuilder('i')                                
                ->where('i.numeroRequerimiento = ?1')
                ->andWhere('s.id <> ?2')
                ->setParameter(1, $numeroRequerimiento)
                ->setParameter(2, $id)                        
                ->getQuery()
                ->getResult();
            }
            else{
                $incidencia= $em->getRepository('MonitorBundle:Mantencion')
                ->createQueryBuilder('i')                                
                ->where('i.numeroRequerimiento = ?1')
                ->setParameter(1, $numeroRequerimiento)
                ->getQuery()
                ->getResult();                
            }                        

            if(!empty($incidencia)){
                $error = true;
                $message = 'N°Req ya existe';                
            }                    
        }        
        return new JsonResponse(array('error' => $error, 'message' => $message));                
    }
    
    public function check2Action(Request $request){
                
        $numeroMantencion= $request->request->get('numeroMantencion');
        $id= $request->request->get('id');
        
        $error = false;
        $message = "N°Mantención válido";        
        
        if (!is_numeric($numeroMantencion)){
            $error = true;
            $message = 'N°Mantención no válido';            
        }             
        
        if(!$error){
            $em = $this->getDoctrine()->getManager();                    
            //Si se esta editando o asignando un servicio se debe proveer el id del servicio
            if($id != null){                
                $mantencion= $em->getRepository('MonitorBundle:Mantencion')
                ->createQueryBuilder('i')                                
                ->where('i.codigoInterno = ?1')
                ->andWhere('s.id <> ?2')
                ->setParameter(1, $numeroMantencion)
                ->setParameter(2, $id)                        
                ->getQuery()
                ->getResult();
            }
            else{
                $mantencion= $em->getRepository('MonitorBundle:Mantencion')
                ->createQueryBuilder('i')                                
                ->where('i.codigoInterno = ?1')
                ->setParameter(1, $numeroMantencion)
                ->getQuery()
                ->getResult();                
            }                        

            if(!empty($mantencion)){
                $error = true;
                $message = 'N°Mantencion ya existe';                
            }                    
        }        
        return new JsonResponse(array('error' => $error, 'message' => $message));                
    }
    
    public function bodyAction(Request $request){
        
        //Obtener parámetros de DataTables
        $sSearch= $request->query->get('sSearch');
        $iSortCol= $request->query->get('iSortCol_0');
        $sSortDir= $request->query->get('sSortDir_0');        
        
        //Obtener parámetros de filtros
        $anio= $request->query->get('anio');
        $mes= $request->query->get('mes');
        $estado= $request->query->get('estado');        
        //////////////////                
        
        $em = $this->getDoctrine()->getManager();                
        
        $parameters = array();                

        $qb = $em->getRepository('MonitorBundle:Mantencion')
                ->createQueryBuilder('m')                
                ->leftJoin('m.tipoRequerimiento', 'tr')
                //->leftJoin('m.incidencia', 'i')
                ->join('m.componente', 'c')
                ->join('m.estadoMantencion', 'e')
                //->join('i.severidad', 'o')
                ->join('m.estadoMantencion', 'em')                                                
                ->where('YEAR(m.fechaIngreso) = ?1')
                ->andWhere('MONTH(m.fechaIngreso) = ?2')
                ->andWhere('e.nombre in (?3)');
        
        $parameters[1] = $anio;
        
        $parameters[2] = $mes;
        
        if($estado == 1)
            $parameters[3]=['En Cola','En Desarrollo','En Testing'];
        else
            $parameters[3]=['Cerrada'];
    
        if($sSearch != null){            
            $qb->andWhere(
            $qb->expr()->orx(
            $qb->expr()->like('m.codigoInterno', '?4'),
            $qb->expr()->like('m.fechaIngreso', '?4'),
            $qb->expr()->like('m.fechaSalida', '?4'),
            $qb->expr()->like('c.nombre', '?4'),            
            $qb->expr()->like('e.nombre', '?4')
           ));
            
           $parameters[4]='%'.$sSearch.'%'; 
        }
        
        if($iSortCol != null){
            
            switch($iSortCol){
                case '0':
                    $qb->orderBy('m.codigoInterno', $sSortDir);
                    break;
                case '1':
                    $qb->orderBy('m.fechaIngreso', $sSortDir);
                    break;                
                case '2':
                    $qb->orderBy('m.fechaSalida', $sSortDir);
                    break;
                case '3':
                    $qb->orderBy('m.hhEstimadas', $sSortDir);
                    break;
                case '4':
                    $qb->orderBy('m.hhEfectias', $sSortDir);
                    break;
                case '5':
                    $qb->orderBy('c.nombre', $sSortDir);
                    break;
                case '6':
                    $qb->orderBy('e.nombre', $sSortDir);
                    break;
            }
        }
                
        $mantenciones= $qb->setParameters($parameters)
                         ->getQuery()
                         ->getResult();   
        
        $estados = $em->getRepository('MonitorBundle:EstadoMantencion')
                ->createQueryBuilder('e')                                
                ->where('e.nombre in (?1)')
                ->setParameter(1, ['En Cola','En Desarrollo','En Testing','Cerrada'])
                ->getQuery()
                ->getResult();                                          
        
        $body = array();              
        $cont = 0;                
        
        foreach($mantenciones as $mantencion){                                                
            
            $fila = array();  
            
            array_push($fila,$mantencion->getCodigoInterno());
            array_push($fila,$mantencion->getFechaInicio()->format('d/m/Y H:i'));
            //array_push($fila,$servicio->getComponente()->getNombre());
            array_push($fila,$mantencion->getFechaSalida()==null?'-':$mantencion->getFechaSalida()->format('d/m/Y H:i'));
            //array_push($fila,$servicio->getTipoServicio()->getTipo()->getNombre());
            //array_push($fila,$mantencion->getHHEstimadas());            
            //array_push($fila,$mantencion->getHHEfectivas());
            //array_push($fila,$mantencion->getComponente()->getNombre());
            //array_push($fila,$incidencia->getPrioridad()->getNombre());  
            
            $fillRatio = intval(100*$mantencion->getHhEfectivas()/$mantencion->getHhEstimadas());
            
            if($fillRatio<=80)
                $color='progress-bar-active';
            if(80<$fillRatio && $fillRatio<=100)
                $color='progress-bar-warning';
            if($fillRatio>100)
                $color='progress-bar-danger';                
            
            $status='progress-bar-striped';
            
            if($mantencion->getEstadoMantencion()->getNombre()=='Cerrada'){
                $status="";
                $color='progress-bar-success';                
            }
            
            $html='<div class="progress"><div class="progress-bar '.$status.' '.$color.'" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:'.$fillRatio.'%"><span class="black-font"><strong class="active">'.$fillRatio.'%</strong></span></div></div>';
            array_push($fila,$html);

            //$html='<select class="mantencion_estadoMantencion" onchange="location = this.value;">';
            $html='<div class="dropdown" style="position:relative">';
            $html=$html.'<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$mantencion->getEstadoMantencion()->getNombre().'<span class="caret"></span></a>';
            $html=$html.'<ul class="dropdown-menu">';            
            
            foreach($estados as $estado)                        
            {                                             
                $usuarios = $em->getRepository('MonitorBundle:Usuario')
                ->createQueryBuilder('u')                                
                ->join('u.estadoMantencion','em')
                ->where('em.nombre = ?1')
                ->setParameter(1, $estado->getNombre())
                ->getQuery()
                ->getResult();
                
                $active="";                    
                
                if(sizeof($usuarios)){                                        
                    
                    if($mantencion->getEstadoMantencion()->getNombre()==$estado->getNombre())
                        $active="active";
                    
                    $html=$html.'<li class="'.$active.'">';   
                    $html=$html.'<a class="trigger right-caret">'.$estado->getNombre().'</a>';
                    $html=$html.'<ul class="dropdown-menu sub-menu">';
                    
                    foreach($usuarios as $usuario){     
                        $active2="";
                        if($mantencion->getUsuario()->getUsername()==$usuario->getUsername())                            
                            $active2="active";
                        $html=$html.'<li class="'.$active2.'"><a href="'.$this->generateUrl('mantencion_status', array('id' => $mantencion->getId(), 'status' => $estado->getNombre(), 'usuario' => $usuario->getUsername())).'">'.$usuario->getUsername().'</a></li>';                            
                    }
                    $html=$html.'</ul>';
                }   
                else{                    
                    if($mantencion->getEstadoMantencion()->getNombre()==$estado->getNombre())
                        $active="active";
                    $html=$html.'<li class="'.$active.'"><a href="'.$this->generateUrl('mantencion_status', array('id' => $mantencion->getId(), 'status' => $estado->getNombre(), 'usuario' => 'null')).'">'.$estado->getNombre().'</a></li>';        
                }
                                                                        
            }   
            $html=$html.'</ul>';
            
            '</select>';    
            
            array_push($fila,$html);
            
            /*
            switch($incidencia->getEstadoIncidencia()->getNombre()){

                case 'En Cola':
                case 'En Gestión FONASA':
                    array_push($fila,'<div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:0%"><span class="black-font">'.$incidencia->getEstadoIncidencia()->getDescripcion().'</span></div></div>');
                    array_push($fila,'<a href="'.$this->generateUrl('incidencia_init', array('id' => $incidencia->getId())).'" role="button" class="btn btn-default">Iniciar</button>');                                        
                    break;                   
                case 'Pendiente MT':
                    array_push($fila,'<div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%"><span>'.$incidencia->getEstadoIncidencia()->getDescripcion().'</span></div></div>');
                    array_push($fila,'<a href="'.$this->generateUrl('incidencia_finish', array('id' => $incidencia->getId())).'" role="button" class="btn btn-default">Finalizar</button>');                    
                    break;                                
                case 'Resuelta MT':                                    
                    array_push($fila,'<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%"><span>'.$incidencia->getEstadoIncidencia()->getDescripcion().'</span></div></div>');
                    array_push($fila,'<a id="'.$incidencia->getId().'" href="'.$this->generateUrl('incidencia_init', array('id' => $incidencia->getId())).'" role="button" class="btn btn-default">Iniciar</button>');                                                            
                    break;
            }              
            */                  
            
            array_push($fila,'<ul><li><a href="'.$this->generateUrl('mantencion_show', array('id' => $mantencion->getId())).'">ver</a></li><li><a href="'.$this->generateUrl('mantencion_edit', array('id' => $mantencion->getId())).'">editar</a></li></ul>');
            
            array_push($body, $fila);
            $cont++;
        }
                
        $output= array(
          'sEcho' => intval($request->request->get('sEcho')),
          'iTotalRecords' => $cont,
          'iTotalDisplayRecords' => $cont,  
          'aaData' => $body          
        );
        
        return new JsonResponse($output);
    }    
    
    /**
     * Displays a form to edit an existing Servicio entity.
     *
     */
    public function statusAction($id, $status, $usuario)
    {                                            
        //$id= $request->request->get('id');
        //$status= $request->request->get('status');
        
        $em = $this->getDoctrine()->getManager();
        
        $mantencion= $em->getRepository('MonitorBundle:Mantencion')
            ->createQueryBuilder('s')                                
            ->where('s.id = ?1')
            ->setParameter(1, $id)
            ->getQuery()
            ->getResult();                            
                
        $estado= $em->getRepository('MonitorBundle:EstadoMantencion')
            ->createQueryBuilder('e')                                
            ->where('e.nombre = ?1')
            ->setParameter(1, $status)
            ->getQuery()
            ->getResult();    
        
        $usuarios= $em->getRepository('MonitorBundle:Usuario')
            ->createQueryBuilder('u')                                
            ->where('u.username = ?1')
            ->setParameter(1, $usuario)
            ->getQuery()
            ->getResult();   

        $mantencion[0]->setEstadoMantencion($estado[0]);
        $mantencion[0]->setIdEstadoMantencion($estado[0]->getId());                                                
        
        if(sizeof($usuarios)){                        
            $mantencion[0]->setUsuario($usuarios[0]);
            $mantencion[0]->setIdUsuario($usuarios[0]->getId());                
        }
        
        switch ($status){
            case 'En Cola': // Si se deja en cola, la fecha de inicio salida se anulan
                $mantencion[0]->setFechaInicio(null);
                $mantencion[0]->setFechaSalida(null);
                break;
            case 'En Desarrollo': // Si se deja en gestión FONASA, no se hace nada
                $incidencia[0]->setFechaUltHH(new\DateTime('now'));
                $mantencion[0]->setFechaInicio(new\DateTime('now'));
                $mantencion[0]->setFechaSalida(null);
                break;            
            case 'En Testing': // Si se deja Pendiente MT se actualiza la fecha inicio
                //$mantencion[0]->setFechaInicio(new\DateTime('now'));
                $mantencion[0]->setFechaSalida(null);
                break;        
            case 'Cerrada':
                $mantencion[0]->setFechaSalida(new\DateTime('now'));
                break;            
        }                

        $em = $this->getDoctrine()->getManager();
        $em->persist($mantencion[0]);
        $em->flush();

        // Guardar el historial del cambio de estado               
        $fechaTerminado=new\DateTime('now');            
        $historial = new HistorialMantencion();

        $historial->setMantencion($mantencion[0]);                     
        $historial->setIdMantencion($mantencion[0]->getId());
        $historial->setEstadoMantencion($estado[0]);
        $historial->setIdEstadoMantencion($estado[0]->getId());            
        $historial->setInicio($fechaTerminado);                   

        $em = $this->getDoctrine()->getManager();
        $em->persist($historial);
        $em->flush();
 
        $this->addFlash(
            'notice',
            'La mantencion asociada al n°Requerimiento:'.$mantencion[0]->getNumeroRequerimiento().' ha cambiado al estado '.$estado[0]->getNombre().'.'
        );   
                                        
        return $this->redirectToRoute('mantencion_index');
    }    
}
