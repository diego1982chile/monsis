<?php

namespace Fonasa\MonitorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Fonasa\MonitorBundle\Entity\Incidencia;
use Fonasa\MonitorBundle\Entity\HistorialIncidencia;
use Fonasa\MonitorBundle\Entity\EstadoIncidencia;
use Fonasa\MonitorBundle\Entity\ObservacionIncidencia;

use Fonasa\MonitorBundle\Form\IncidenciaType;

use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Servicio controller.
 *
 */
class IncidenciaController extends Controller
{
    /**
     * Lists all Servicio entities.
     *
     */
    public function indexAction()
    {                
        return $this->render('MonitorBundle:incidencia:index.html.twig');         
    }

    /**
     * Creates a new Servicio entity.
     *
     */
    public function newAction(Request $request)
    {
        $incidencia = new Incidencia();
        
        $em = $this->getDoctrine()->getManager();        
        
        $form = $this->createForm('Fonasa\MonitorBundle\Form\IncidenciaType', $incidencia, array('assign' => false));
        $form->handleRequest($request);                                
                
        if ($form->isSubmitted() && $form->isValid()) {
            $numeroTicket=$request->request->get('incidencia')['numeroTicket'];                                                
            
            $fechaIngreso=new\DateTime('now');
                        
            // Si es Incidencia, por defecto se crea con estado 'Pendient MT'
            $estado= $em->getRepository('MonitorBundle:EstadoIncidencia')
                ->createQueryBuilder('e')                                
                ->where('e.nombre = ?1')
                ->setParameter(1, 'Pendiente MT')
                ->getQuery()
                ->getResult();                        
                                    
            $incidencia->setEstadoIncidencia($estado[0]);
            $incidencia->setIdEstadoIncidencia($estado[0]->getId());            
            //$incidencia->setNumeroTicket($numeroTicket);
            $incidencia->setFechaIngreso($fechaIngreso);
            $incidencia->setHhEfectivas(0);
            
            switch ($incidencia->getEstadoIncidencia()->getNombre()){
                case 'En Cola': // Si se deja en cola, la fecha de inicio salida se anulan
                    //$incidencia[0]->setFechaInicio(null);
                    //$incidencia[0]->setFechaSalida(null
                    $incidencia->setFechaSalida(null);
                    break;
                case 'En Gestión FONASA': // Si se deja en gestión FONASA, no se hace nada
                    $incidencia->setFechaSalida(null);
                    break;            
                case 'Pendiente MT': // Si se deja Pendiente MT se actualiza la fecha inicio
                    $incidencia->setFechaInicio(new\DateTime('now'));
                    $incidencia->setFechaSalida(null);                    
                    $incidencia->setFechaUltHH(new\DateTime('now'));
                    break;        
                case 'Resuelta MT':
                    $incidencia->setFechaSalida(new\DateTime('now'));
                    break;            
            }
            
            //die($incidencia);                        
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($incidencia);                                    
            $em->flush();
                        
            $this->addFlash(
                'notice',
                'Se ha ingresado una nueva incidencia.| La incidencia está asociada al número de ticket:'.$numeroTicket.'.| La incidencia está en cola, y puede ser asignada en el panel de incidencias'
            );               

            return $this->redirectToRoute('incidencia_show', array('id' => $incidencia->getId()));
        }

        return $this->render('MonitorBundle:incidencia:new.html.twig', array(
            'incidencia' => $incidencia,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Seteamos el id de la incidencia en el request y realizamos un forward al action new del 
     * MantencionController
     */
    public function mantencionAction(Request $request, $id)
    {
        $request->attributes->set('idIncidencia', $id);
        
        return $this->forward('MonitorBundle:Mantencion:new', array(
            'request'  => $request      
        ));
    }    

    /**
     * Finds and displays a Incidencia entity.
     *
     */
    public function showAction(Incidencia $incidencia)
    {        
        
        $deleteForm = $this->createDeleteForm($incidencia);

        return $this->render('MonitorBundle:incidencia:show.html.twig', array(
            'incidencia' => $incidencia,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Displays a form to edit an existing Servicio entity.
     *
     */
    public function editAction(Request $request, Incidencia $incidencia)
    {
        $deleteForm = $this->createDeleteForm($incidencia);
        
        $editForm = $this->createForm('Fonasa\MonitorBundle\Form\IncidenciaType', $incidencia, array('assign' => false));                        
        
        $editForm->handleRequest($request);
                
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($incidencia);
            $em->flush();

            //return $this->redirectToRoute('incidencia_edit', array('id' => $incidencia->getId()));
            
            $this->addFlash(
                'notice',
                'Se ha modificado la incidencia N°Ticket '.$incidencia->getNumeroTicket().'.'
            );               

            return $this->redirectToRoute('incidencia_show', array('id' => $incidencia->getId()));
        }

        return $this->render('MonitorBundle:incidencia:edit.html.twig', array(
            'incidencia' => $incidencia,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }     
            
    /**
     * Displays a form to edit an existing Servicio entity.
     *
     */
    public function statusAction($id, $status, $observacion)
    {                                            
        //$id= $request->request->get('id');
        //$status= $request->request->get('status');
        
        $em = $this->getDoctrine()->getManager();
        
        $incidencia= $em->getRepository('MonitorBundle:Incidencia')
            ->createQueryBuilder('s')                                
            ->where('s.id = ?1')
            ->setParameter(1, $id)
            ->getQuery()
            ->getResult();                            
                
        $estado= $em->getRepository('MonitorBundle:EstadoIncidencia')
            ->createQueryBuilder('e')                                
            ->where('e.nombre = ?1')
            ->setParameter(1, $status)
            ->getQuery()
            ->getResult();                                  

        $incidencia[0]->setEstadoIncidencia($estado[0]);
        $incidencia[0]->setIdEstadoIncidencia($estado[0]->getId());                
        
        switch ($status){
            case 'En Cola': // Si se deja en cola, la fecha de inicio salida se anulan
                //$incidencia[0]->setFechaInicio(null);
                //$incidencia[0]->setFechaSalida(null
                $incidencia[0]->setFechaSalida(null);
                break;
            case 'En Gestión FONASA': // Si se deja en gestión FONASA, no se hace nada
                $incidencia[0]->setFechaSalida(null);
                break;            
            case 'Pendiente MT': // Si se deja Pendiente MT se actualiza la fecha inicio
                $incidencia[0]->setFechaInicio(new\DateTime('now'));
                $incidencia[0]->setFechaSalida(null);
                //$incidencia[0]->setHhEfectivas(0);
                $incidencia[0]->setFechaUltHH(new\DateTime('now'));
                break;        
            case 'Resuelta MT':
                // Si se deja como resuelta, agregar observaciones...
                $incidencia[0]->setFechaSalida(new\DateTime('now'));                
                $observacionIncidencia= new ObservacionIncidencia();
                $observacionIncidencia->setObservacion($observacion);
                $observacionIncidencia->setIncidencia($incidencia[0]);
                $observacionIncidencia->setIdIncidencia($incidencia[0]->getId());
                $em->persist($observacionIncidencia);
                $em->flush();                
                break;            
        }
        
        $em->persist($incidencia[0]);
        $em->flush();

        // Guardar el historial del cambio de estado               
        $fechaTerminado=new\DateTime('now');            
        $historial = new HistorialIncidencia();

        $historial->setIncidencia($incidencia[0]);                     
        $historial->setIdIncidencia($incidencia[0]->getId());
        $historial->setEstadoIncidencia($estado[0]);
        $historial->setIdEstadoIncidencia($estado[0]->getId());            
        $historial->setInicio($fechaTerminado);                   

        $em = $this->getDoctrine()->getManager();
        $em->persist($historial);
        $em->flush();
 
        $this->addFlash(
            'notice',
            'La incidencia asociada al n°Ticket:'.$incidencia[0]->getNumeroTicket().' ha cambiado al estado '.$estado[0]->getNombre().'.'
        );   
                                        
        return $this->redirectToRoute('incidencia_index');
    }                                
    
    /**
     * Deletes a Incidencia entity.
     *
     */
    public function deleteAction(Request $request, Incidencia $incidencia)
    {
        $form = $this->createDeleteForm($incidencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($incidencia);
            $em->flush();
        }

        return $this->redirectToRoute('incidencia_index');
    }

    /**
     * Creates a form to delete a Servicio entity.
     *
     * @param Incidencia $incidencia The Servicio entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Incidencia $incidencia)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('incidencia_delete', array('id' => $incidencia->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    public function checkAction(Request $request){
                
        $numeroTicket= $request->request->get('numeroTicket');
        $id= $request->request->get('id');
        
        $error = false;
        $message = "N°Ticket válido";                        
                                
        if (!is_numeric($numeroTicket)){             
            $error = true;
            $message = 'N°Ticket de formato no válido';            
        }             
        
        if(!$error){
            $em = $this->getDoctrine()->getManager();                    
            //Si se esta editando o asignando un servicio se debe proveer el id del servicio
            if($id != null){                
                $incidencia= $em->getRepository('MonitorBundle:Incidencia')
                ->createQueryBuilder('i')                                
                ->where('i.numeroTicket = ?1')
                ->andWhere('s.id <> ?2')
                ->setParameter(1, $numeroTicket)
                ->setParameter(2, $id)                        
                ->getQuery()
                ->getResult();
            }
            else{
                $incidencia= $em->getRepository('MonitorBundle:Incidencia')
                ->createQueryBuilder('i')                                
                ->where('i.numeroTicket = ?1')
                ->setParameter(1, $numeroTicket)
                ->getQuery()
                ->getResult();                
            }                        

            if(!empty($incidencia)){
                $error = true;
                $message = 'Ya existe una incidencia con este n° de Ticket';                
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

        $qb = $em->getRepository('MonitorBundle:Incidencia')
                ->createQueryBuilder('i')                
                ->join('i.categoriaIncidencia', 'ci')
                ->join('i.componente', 'c')
                ->join('i.severidad', 'o')
                ->join('i.estadoIncidencia', 'e')                                                
                ->where('YEAR(i.fechaReporte) = ?1')
                ->andWhere('MONTH(i.fechaReporte) = ?2')
                ->andWhere('e.nombre in (?3)');
        
        $parameters[1] = $anio;
        
        $parameters[2] = $mes;
        
        if($estado == 1)
            $parameters[3]=[/*'En Cola',*/'En Gestión FONASA','Pendiente MT'];
        else
            $parameters[3]=['Resuelta MT'];
    
        if($sSearch != null){            
            $qb->andWhere(
            $qb->expr()->orx(
            $qb->expr()->like('i.numeroTicket', '?4'),
            $qb->expr()->like('ci.nombre', '?4'),
            $qb->expr()->like('c.nombre', '?4'),
            $qb->expr()->like('o.nombre', '?4'),            
            $qb->expr()->like('e.nombre', '?4')
           ));
            
           $parameters[4]='%'.$sSearch.'%'; 
        }
        
        if($iSortCol != null){
            
            switch($iSortCol){
                case '0':
                    $qb->orderBy('i.numeroTicket', $sSortDir);
                    break;
                case '1':
                    $qb->orderBy('i.fechaReporte', $sSortDir);
                    break;                
                case '2':
                    $qb->orderBy('ci.nombre', $sSortDir);
                    break;
                case '3':
                    $qb->orderBy('c.nombre', $sSortDir);
                    break;
                case '4':
                    $qb->orderBy('o.nombre', $sSortDir);
                    break;
                case '5':
                    $qb->orderBy('e.nombre', $sSortDir);
                    break;
            }
        }
                
        $incidencias= $qb->setParameters($parameters)
                         ->getQuery()
                         ->getResult();   
        
        $estados = $em->getRepository('MonitorBundle:EstadoIncidencia')
                ->createQueryBuilder('e')                                
                ->where('e.nombre in (?1)')
                ->setParameter(1, [/*'En Cola',*/'En Gestión FONASA','Pendiente MT','Resuelta MT'])
                ->getQuery()
                ->getResult();                                          
        
        $body = array();              
        $cont = 0;                
        
        foreach($incidencias as $incidencia){                        
            
            $fila = array();  
            
            $fillRatio = intval(100*$incidencia->getHhEfectivas()/$incidencia->getSeveridad()->getSla());                        
            
            $color='';
                        
            if(80<$fillRatio && $fillRatio<=100)
                $color='orange';
            if($fillRatio>100)
                $color='red';                                                    
            if($incidencia->getEstadoIncidencia()->getNombre()=='Resuelta MT')
                $color='green';                            
            
            $html='<div class="c100 p'.min($fillRatio,100).' small '.$color.'"><span>'.$fillRatio.'%</span><div class="slice"><div class="bar"></div><div class="fill"></div></div></div>';
            
            //$html='<div class="progress"><div class="progress-bar '.$color.'" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:'.$fillRatio.'%"><span class="black-font"><strong class="active">'.$fillRatio.'%</strong></span></div></div>';
            array_push($fila,$html);                                                
            
            array_push($fila,$incidencia->getNumeroTicket());
            array_push($fila,$incidencia->getFechaInicio()==null?'-':$incidencia->getFechaInicio()->format('d/m/Y H:i'));
            array_push($fila,$incidencia->getFechaSalida()==null?'-':$incidencia->getFechaSalida()->format('d/m/Y H:i'));            
            //array_push($fila,$servicio->getComponente()->getNombre());
            array_push($fila,$incidencia->getSeveridad()->getNombre());
            //array_push($fila,$servicio->getTipoServicio()->getTipo()->getNombre());            
            //array_push($fila,$incidencia->getPrioridad()->getNombre());                                      
            
            $html='<div class="dropdown" style="position:relative">';
            $html=$html.'<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$incidencia->getEstadoIncidencia()->getNombre().'<span class="caret"></span></a>';
            $html=$html.'<ul class="dropdown-menu">';            
            
            /*
            $html='<select class="incidencia_estadoIncidencia" onchange="location = this.value;">';
            $selected;
            */
            $active="";                                
            
            foreach($estados as $estado)                        
            {
                /*
                if($estado->getNombre()==$incidencia->getEstadoIncidencia()->getNombre())
                    $selected='selected';
                else
                    $selected=null;
                
                $html=$html.'<option value='.$this->generateUrl('incidencia_status', array('id' => $incidencia->getId(), 'status' => $estado->getNombre())).' '.$selected.'>'.$estado->getNombre().'</button>';
                */
                $active="";  
                
                if($incidencia->getEstadoIncidencia()->getNombre()==$estado->getNombre())
                    $active="active";
                $html=$html.'<li class="'.$active.'"><a class="estados" href="'.$this->generateUrl('incidencia_status', array('id' => $incidencia->getId(), 'status' => $estado->getNombre(), 'observacion' => 'null')).'">'.$estado->getNombre().'</a></li>';                                        
            }            
            //$html=$html.'</select>';    
            
            $html=$html.'</ul>';                        
            
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
            
            array_push($fila,'<ul><li><a href="'.$this->generateUrl('incidencia_show', array('id' => $incidencia->getId())).'">ver</a></li><li><a href="'.$this->generateUrl('incidencia_edit', array('id' => $incidencia->getId())).'">editar</a></li></ul>');
            
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
}
