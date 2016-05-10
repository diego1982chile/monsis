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
            //$fechaInicio=$request->request->get('mantencion')['fechaInicio']['date'];                                                
            
            $fechaIngreso=new\DateTime('now');
            
            if($inicioProgramado)
                $nomEstado='En Cola';                                                        
            else
                $nomEstado='En Desarrollo';                
                                    
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
            $mantencion->setTocada($fechaIngreso);
            $mantencion->setHhEfectivas(0);
            
            switch ($mantencion->getEstadoMantencion()->getNombre()){
                case 'En Cola': // Si se deja en cola, la fecha de inicio salida se anulan
                    //$incidencia[0]->setFechaInicio(null);
                    //$incidencia[0]->setFechaSalida(null
                    $mantencion->setFechaSalida(null);
                    $mantencion->setFechaUltHH(new\DateTime('now'));
                    break;
                case 'En Desarrollo': // Si se deja en gestión FONASA, no se hace nada
                    $mantencion->setFechaSalida(null);
                    $mantencion->setFechaInicio(new\DateTime('now'));
                    $mantencion->setFechaUltHH(new\DateTime('now'));
                    break;                            
            }
            
            //$em = $this->getDoctrine()->getManager();
            $em->persist($mantencion);
            $em->flush();
            
            //echo '"'.$mantencion->getId().'"';
            
            if($inicioProgramado){
                $msg='La mantención está en cola y tiene un inicio programado para el '.$mantencion->getfechaInicio()->format('d/m/Y H:i').', fecha en la que será automáticamente asignada al área de desarrollo.';
                //Si es inicio programado crear el scheduledEvent en la BD
                $connection = $em->getConnection();       
                $query="CREATE DEFINER=`root`@`localhost` EVENT `scheduler_inicio_mantencion_".$mantencion->getId()."` ON SCHEDULE AT '".$mantencion->getfechaInicio()->format('Y-m-d H:i')."' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE mantencion set ID_ESTADO_MANTENCION=2,FECHA_INICIO_MANTENCION=SYSDATE() WHERE id=".$mantencion->getId()." AND ID_ESTADO_MANTENCION=1;";
                $connection->exec($query);
            }
            else{
                 //Si no es inicio programado setear la fecha de inicio
                $mantencion->setFechaInicio($fechaIngreso);
                $msg='La mantención se ha iniciado y ha quedado asignada al área de desarrollo.';                            
            }

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
        $editForm = $this->createForm('Fonasa\MonitorBundle\Form\MantencionType', $mantencion, array(
                                      'idIncidencia' => $mantencion->getIdIncidencia()
                                      ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $mantencion->setTocada(new\DateTime('now'));
            
            $em->persist($mantencion);
            $em->flush();
            
            $this->addFlash(
                'notice',
                'Se ha modificado la mantención N°'.$mantencion->getCodigoInterno().'.'
            );

            //return $this->redirectToRoute('mantencion_edit', array('id' => $mantencion->getId()));
            return $this->redirectToRoute('mantencion_show', array('id' => $mantencion->getId()));
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
            
            $this->addFlash(
                'notice',
                'Se ha eliminado la mantención N°'.$mantencion->getCodigoInterno().'.'
            );
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
        // Si es numerico, validar que sea entero
        if (floor($numeroRequerimiento)!=$numeroRequerimiento){            
            $error = true;
            $message = 'N°Req no válido';            
        }
        // Si es entero, validar que no empiece con 0
        if(substr($numeroRequerimiento, 0, 1) == '0'){
            $error = true;
            $message = 'N°Req no válido';
        }        
        // Si es numerico, validar que sea entero positivo menor a un maximo
        if($numeroRequerimiento<=0 || $numeroRequerimiento>999999999){
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
                ->andWhere('i.id <> ?2')
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
        
        // Si es numerico, validar que sea entero
        if (floor($numeroMantencion)!=$numeroMantencion){            
            $error = true;
            $message = 'N°Mantención no válido';            
        }
        // Si es entero, validar que no empiece con 0
        if(substr($numeroMantencion, 0, 1) == '0'){
            $error = true;
            $message = 'N°Mantención no válido';
        }        
        // Si es numerico, validar que sea entero positivo menor a un maximo
        if($numeroMantencion<=0 || $numeroMantencion>999999999){
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
                ->andWhere('i.id <> ?2')
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
    
    public function check3Action(Request $request){
                
        $hhEstimadas= $request->request->get('hhEstimadas');      
        
        $error = false;
        $message = "N°válido";                        
        
        if (!is_numeric($hhEstimadas)){
            $error = true;
            $message = 'N°no válido';            
        }             
        
        // Si es numerico, validar que sea entero
        if (floor($hhEstimadas)!=$hhEstimadas){            
            $error = true;
            $message = 'N°no válido';            
        }
        // Si es entero, validar que no empiece con 0
        if(substr($hhEstimadas, 0, 1) == '0'){
            $error = true;
            $message = 'N°no válido';
        }        
        // Si es numerico, validar que sea entero positivo menor a un maximo
        if($hhEstimadas<=0 || $hhEstimadas>999999999){
            $error = true;
            $message = 'N°no válido';                        
        }
                
        return new JsonResponse(array('error' => $error, 'message' => $message));                
    }
    
    public function bodyAction(Request $request){
        
        //Obtener parámetros de DataTables
        $sSearch= $request->query->get('sSearch');
        $iSortCol= $request->query->get('iSortCol_0');
        $sSortDir= $request->query->get('sSortDir_0');
        $iDisplayStart= $request->query->get('iDisplayStart');      
        $iDisplayLength= $request->query->get('iDisplayLength');              
        //////////////////////////////////
        
        //Obtener parámetros de filtros
        $componente= $request->query->get('componente');
        $anio= $request->query->get('anio');
        $mes= $request->query->get('mes');
        $estado= $request->query->get('estado');   
        $resetFiltros = $request->query->get('resetFiltros');  
        //////////////////                           
        
        $em = $this->getDoctrine()->getManager();                
        
        $parameters = array();                

        $qb = $em->getRepository('MonitorBundle:Mantencion')
                ->createQueryBuilder('m')                
                ->leftJoin('m.tipoRequerimiento', 'tr')
                ->leftJoin('m.incidencia', 'i')
                ->join('m.componente', 'c')
                ->join('m.estadoMantencion', 'e')
                //->join('i.severidad', 'o')
                ->join('m.estadoMantencion', 'em')                                                                
                ->where('YEAR(m.fechaInicio) = ?1')
                ->andWhere('MONTH(m.fechaInicio) = ?2');                
        
        $parameters[1] = $anio;
        
        $parameters[2] = $mes;
        
        if($componente != -1){
            $qb->andWhere('c.id = ?3');
            $parameters[3] = $componente;
        }
        
        if($estado != -1){
            $qb->andWhere('e.nombre in (?4)');                
        }
        
        switch($estado){
            case 1:
                $parameters[4]=['En Cola','En Desarrollo','En Certificación','En Testing'];
                break;
            case 2:
                $parameters[4]=['Cerrada'];
                break;
        } 
    
        if($sSearch != null){            
            $qb->andWhere(
            $qb->expr()->orx(
            $qb->expr()->like('m.codigoInterno', '?5'),
            $qb->expr()->like('m.numeroRequerimiento', '?5'),
                    
            $qb->expr()->like('m.fechaIngreso', '?5'),
            $qb->expr()->like('m.fechaSalida', '?5'),
            $qb->expr()->like('c.nombre', '?5'),            
            $qb->expr()->like('e.nombre', '?5')
           ));
            
           $parameters[5]='%'.$sSearch.'%'; 
        }
        
        if($iSortCol != null){
            
            switch($iSortCol){
                case '7':
                    $qb->orderBy('m.tocada', 'DESC');
                    break;
                case '1':
                    $qb->orderBy('m.numeroRequerimiento', $sSortDir);
                    $qb->addOrderBy('i.numeroTicket', $sSortDir);                     
                    break;
                case '2':
                    $qb->orderBy('m.codigoInterno', $sSortDir);
                    break;                
                case '2':
                    $qb->orderBy('m.fechaInicio', $sSortDir);
                    break;                
                case '3':
                    $qb->orderBy('m.fechaSalida', $sSortDir);
                    break;
                case '4':
                    $qb->orderBy('s.nombre', $sSortDir);
                    break;                
                case '5':
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
                ->setParameter(1, ['En Cola','En Desarrollo','En Testing','En Certificación','Cerrada'])
                ->getQuery()
                ->getResult();                                          
        
        $body = array();        
        
        $hayPaP = false;
        
        foreach($mantenciones as $key => $mantencion){                                                
            
            if($iDisplayLength != -1){
                if($key < $iDisplayStart || $key >= $iDisplayStart+$iDisplayLength)
                    continue;
            }
            
            $fila = array();  
            
            $color='';
            
            $fillRatio = intval(100*$mantencion->getHhEfectivas()/$mantencion->getHhEstimadas());
                        
            if(80<$fillRatio && $fillRatio<=100)
                $color='orange';
            if($fillRatio>100)
                $color='red';                                                    
            if($mantencion->getEstadoMantencion()->getNombre()=='Cerrada')
                $color='green';                            
            
            $html='<div class="c100 p'.min($fillRatio,100).' small '.$color.'"><span>'.$fillRatio.'%</span><div class="slice"><div class="bar"></div><div class="fill"></div></div></div>';
            
            //$html='<div class="progress"><div class="progress-bar '.$color.'" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:'.$fillRatio.'%"><span class="black-font"><strong class="active">'.$fillRatio.'%</strong></span></div></div>';
            array_push($fila,$html);                                                
            
            array_push($fila,$mantencion->getIncidencia()==null?'Req'.$mantencion->getNumeroRequerimiento():'Ticket'.$mantencion->getIncidencia()->getNumeroTicket());            
            $prefix;
            switch ($mantencion->getComponente()->getNombre()){
                case 'SIGGES':
                    $prefix='SIGG';
                    break;
                case 'GGPF':
                    $prefix='GGPF';
                    break;                
                case 'PM':  
                    $prefix='PM';
                    break;                
                case 'BGI / DataWareHouse':
                    $prefix='DWH';
                    break;                
            }
            array_push($fila,$mantencion->getTipoMantencion()->getNombre()=='Mantención Evolutiva'?$prefix.'-ME'.$mantencion->getCodigoInterno():$prefix.'-MC'.$mantencion->getCodigoInterno());
            array_push($fila,$mantencion->getFechaInicio()==null?'-':$mantencion->getFechaInicio()->format('d/m/Y H:i'));
            //array_push($fila,$servicio->getComponente()->getNombre());
            array_push($fila,$mantencion->getFechaSalida()==null?'-':$mantencion->getFechaSalida()->format('d/m/Y H:i'));
            //array_push($fila,$servicio->getTipoServicio()->getTipo()->getNombre());
            //array_push($fila,$mantencion->getHHEstimadas());            
            //array_push($fila,$mantencion->getHHEfectivas());
            //array_push($fila,$mantencion->getComponente()->getNombre());
            //array_push($fila,$incidencia->getPrioridad()->getNombre());                                                                                      

            //$html='<select class="mantencion_estadoMantencion" onchange="location = this.value;">';
            $html='<div class="dropdown" style="position:relative">';
            $html=$html.'<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$mantencion->getEstadoMantencion()->getNombre().'<span class="caret"></span></a>';
            
            if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
                
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
                                if($mantencion->getUsuario()!=null){
                                    if($mantencion->getUsuario()->getUsername()==$usuario->getUsername())                            
                                        $active2="active";
                                }
                                $html=$html.'<li class="'.$active2.'"><a href="'.$this->generateUrl('mantencion_status', array('id' => $mantencion->getId(), 'status' => $estado->getNombre(), 'usuario' => $usuario->getUsername())).'">'.$usuario->getUsername().'</a></li>';                            

                        }
                        $html=$html.'</ul>';
                    }   
                    else{                    
                        if($mantencion->getEstadoMantencion()->getNombre()==$estado->getNombre())
                            $active="active";
                        $html=$html.'<li class="'.$active.'"><a class="estados" href="'.$this->generateUrl('mantencion_status', array('id' => $mantencion->getId(), 'status' => $estado->getNombre(), 'usuario' => 'null')).'">'.$estado->getNombre().'</a></li>';        
                    }
                }   
            }   
            $html=$html.'</ul>';
            
            '</select>';    
            
            array_push($fila,$html);
            
            if($mantencion->getEstadoMantencion()->getNombre()=='En Certificación')
                $hayPaP=true;
            
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
            
            $html='<ul><li><a href="'.$this->generateUrl('mantencion_show', array('id' => $mantencion->getId())).'">ver</a></li>';
            
            if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))                
                $html=$html.'<li><a href="'.$this->generateUrl('mantencion_edit', array('id' => $mantencion->getId())).'">editar</a></li></ul>';
            
            array_push($fila,$html);
            
            //Se añade campo tocada
            array_push($fila, $mantencion->getTocada());
            
            array_push($body, $fila);            
        }
        
        $pap = null;
            
        if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            if($hayPaP)
                $pap = '<a id="cerrar" href="'.$this->generateUrl('mantencion_cerrar', array('componente' => 'null_1', 'mes' => 'null_2', 'anio' => 'null_3')).'" role="button" class="btn btn-link">Cerrar Mantenciones en Certificación</button>';
                //$html= '<a href="'.$this->generateUrl('incidencia_status', array('id' => $incidencia->getId(), 'status' => $estado->getNombre(), 'observacion' => 'null')).'">'.$estado->getNombre().'</a></li>';                                        
        }        
                
        $output= array(
          'sEcho' => intval($request->request->get('sEcho')),
          'iTotalRecords' => sizeof($mantenciones),
          'iTotalDisplayRecords' => sizeof($mantenciones),  
          'aaData' => $body,
          'pap' => $pap
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
        $mantencion[0]->setTocada(new\DateTime('now'));                
        
        if(sizeof($usuarios)){                        
            $mantencion[0]->setUsuario($usuarios[0]);
            $mantencion[0]->setIdUsuario($usuarios[0]->getId());                
        }
        
        switch ($status){
            case 'En Cola': // Si se deja en cola, la fecha de inicio salida se anulan
                //$mantencion[0]->setFechaInicio(null);
                $mantencion[0]->setFechaSalida(null);
                $mantencion[0]->setUsuario(null);
                break;
            case 'En Desarrollo': // Si se deja en gestión FONASA, no se hace nada
                $mantencion[0]->setFechaUltHH(new\DateTime('now'));
                if($mantencion[0]->getFechaInicio() == null)
                    $mantencion[0]->setFechaInicio(new\DateTime('now'));
                $mantencion[0]->setFechaSalida(null);
                break;            
            case 'En Testing': // Si se deja Pendiente MT se actualiza la fecha inicio
                //$mantencion[0]->setFechaInicio(new\DateTime('now'));
                $mantencion[0]->setFechaSalida(null);
                break;        
            case 'Cerrada':
                $mantencion[0]->setFechaSalida(new\DateTime('now'));
                $mantencion[0]->setUsuario(null);
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
        
        if($mantencion[0]->getIncidencia() == null ){
            $this->addFlash(
            'notice',
            'La mantencion asociada al n°Requerimiento:'.$mantencion[0]->getNumeroRequerimiento().' ha cambiado al estado '.$estado[0]->getNombre().'.'
            );              
        }       
        else{
            $this->addFlash(
            'notice',
            'La mantencion asociada al n°Ticket:'.$mantencion[0]->getIncidencia()->getNumeroTicket().' ha cambiado al estado '.$estado[0]->getNombre().'.'
            );              
        }
                                        
        return $this->redirectToRoute('mantencion_index');
    } 
    
    /**
     * Displays a form to edit an existing Servicio entity.
     *
     */
    public function cerrarAction($componente, $mes, $anio)
    {                                           
        
        $em = $this->getDoctrine()->getManager();
        
        $fechaTerminado=new\DateTime('now');      
        
        $parameters = array();                

        $qb = $em->getRepository('MonitorBundle:Mantencion')
                ->createQueryBuilder('m')                
                //->join('i.severidad', 'o')
                ->join('m.componente', 'c')                                                                
                ->join('m.estadoMantencion', 'em')                                                                
                ->where('em.nombre in (?1)');
        
        $parameters[1] = 'En Certificación';                                                
    
        if($componente != -1){            
            $qb->andWhere('c.id = ?2');                                                           
            $parameters[2] = $componente;
        }   
        
        $qb->andWhere('MONTH(m.fechaIngreso) = ?3');                
        $parameters[3] = $mes;
        
        $qb->andWhere('YEAR(m.fechaIngreso) = ?4');                
        $parameters[4] = $anio;                
                
        $mantenciones= $qb->setParameters($parameters)
                         ->getQuery()
                         ->getResult();                                          
                
        $estado= $em->getRepository('MonitorBundle:EstadoMantencion')
                    ->createQueryBuilder('e')                                
                    ->where('e.nombre = ?1')
                    ->setParameter(1, 'Cerrada')
                    ->getQuery()
                    ->getResult();        
        
        foreach($mantenciones as $mantencion){
            $mantencion->setEstadoMantencion($estado[0]);
            $mantencion->setIdEstadoMantencion($estado[0]->getId());                
            $mantencion->setFechaSalida($fechaTerminado);
            $mantencion->setTocada(new\DateTime('now'));                

            $em = $this->getDoctrine()->getManager();
            $em->persist($mantencion);
            $em->flush();

            // Guardar el historial del cambio de estado               
            $fechaInicio=new\DateTime('now');            
            $historial = new HistorialMantencion();

            $historial->setMantencion($mantencion);                     
            $historial->setIdMantencion($mantencion->getId());
            $historial->setEstadoMantencion($estado[0]);
            $historial->setIdEstadoMantencion($estado[0]->getId());            
            $historial->setInicio($fechaInicio);                   

            $em = $this->getDoctrine()->getManager();
            $em->persist($historial);
            $em->flush();    
        }        
 
        $this->addFlash(
            'notice',
            'Todas las mantenciones en certificación han sido cerradas.| Estas pueden ser visualizadas mediante el filtro resueltas en el panel principal.'
        );   
                
        //return $this->render('MonitorBundle:servicio:index.html.twig');                         
        return $this->redirectToRoute('mantencion_index');
    }            
}
