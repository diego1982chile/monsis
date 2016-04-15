<?php

namespace Fonasa\MonitorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Fonasa\MonitorBundle\Entity\Incidencia;
use Fonasa\MonitorBundle\Entity\HistorialIncidencia;
use Fonasa\MonitorBundle\Entity\EstadoIncidencia;

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
                        
            // Si es Incidencia, por defecto se crea con estado 'En Gestión FONASA'
            $estado= $em->getRepository('MonitorBundle:EstadoIncidencia')
                ->createQueryBuilder('e')                                
                ->where('e.nombre = ?1')
                ->setParameter(1, 'En Cola')
                ->getQuery()
                ->getResult();                        
                                    
            $incidencia->setEstadoIncidencia($estado[0]);
            $incidencia->setIdEstadoIncidencia($estado[0]->getId());            
            //$incidencia->setNumeroTicket($numeroTicket);
            $incidencia->setFechaIngreso($fechaIngreso);
            
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
    public function initAction($id)
    {                                           
        
        $em = $this->getDoctrine()->getManager();
        $fechaInicio=new\DateTime('now');       
        
        $incidencia= $em->getRepository('MonitorBundle:Incidencia')
            ->createQueryBuilder('s')                                
            ->where('s.id = ?1')
            ->setParameter(1, $id)
            ->getQuery()
            ->getResult();                            
                
        $estado= $em->getRepository('MonitorBundle:EstadoIncidencia')
            ->createQueryBuilder('e')                                
            ->where('e.nombre = ?1')
            ->setParameter(1, 'Pendiente MT')
            ->getQuery()
            ->getResult();                    

        $incidencia[0]->setEstadoIncidencia($estado[0]);
        $incidencia[0]->setIdEstadoIncidencia($estado[0]->getId()); 
        $incidencia[0]->setFechaInicio($fechaInicio);

        $em = $this->getDoctrine()->getManager();
        $em->persist($incidencia[0]);
        $em->flush();

        // Guardar el historial del cambio de estado               
        $fechaInicio=new\DateTime('now');            
        $historial = new HistorialIncidencia();

        $historial->setIncidencia($incidencia[0]);                     
        $historial->setIdIncidencia($incidencia[0]->getId());
        $historial->setEstadoIncidencia($estado[0]);
        $historial->setIdEstadoIncidencia($estado[0]->getId());            
        $historial->setInicio($fechaInicio);                   

        $em = $this->getDoctrine()->getManager();
        $em->persist($historial);
        $em->flush();

 
        $this->addFlash(
            'notice',
            'Se ha iniciado la incidencia '.$incidencia[0]->getNumeroTicket().'.| El incidencia esta actualmente en estado "Pendiente MT" y puede ser finalizada en el panel principal.'
        );   
                
        //return $this->render('MonitorBundle:incidencia:index.html.twig');                         
        return $this->redirectToRoute('incidencia_index');
    }    
    
    
    /**
     * Displays a form to edit an existing Servicio entity.
     *
     */
    public function finishAction($id)
    {                                           
        
        $em = $this->getDoctrine()->getManager();
        $fechaTerminado=new\DateTime('now');       
        
        $incidencia= $em->getRepository('MonitorBundle:Incidencia')
            ->createQueryBuilder('s')                                
            ->where('s.id = ?1')
            ->setParameter(1, $id)
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
        $incidencia[0]->setFechaSalida($fechaTerminado);

        $em = $this->getDoctrine()->getManager();
        $em->persist($incidencia[0]);
        $em->flush();

        // Guardar el historial del cambio de estado               
        $fechaInicio=new\DateTime('now');            
        $historial = new HistorialIncidencia();

        $historial->setIncidencia($incidencia[0]);                     
        $historial->setIdIncidencia($incidencia[0]->getId());
        $historial->setEstadoIncidencia($estado[0]);
        $historial->setIdEstadoIncidencia($estado[0]->getId());            
        $historial->setInicio($fechaInicio);                   

        $em = $this->getDoctrine()->getManager();
        $em->persist($historial);
        $em->flush();

 
        $this->addFlash(
            'notice',
            'Se ha finalizado la incidencia N°Ticket '.$incidencia[0]->getNumeroTicket().'.| Esta puede ser visualizada en el panel principal mediante el filtro "Finalizados".'
        );   
                
        //return $this->render('MonitorBundle:incidencia:index.html.twig');                         
        return $this->redirectToRoute('incidencia_index');
    }    
        
    /**
     * Displays a form to edit an existing Servicio entity.
     *
     */
    public function desaAction($id)
    {                                           
        
        $em = $this->getDoctrine()->getManager();
        
        $servicio= $em->getRepository('MonitorBundle:Servicio')
            ->createQueryBuilder('s')                                
            ->where('s.id = ?1')
            ->setParameter(1, $id)
            ->getQuery()
            ->getResult();                            
                
        $estado= $em->getRepository('MonitorBundle:Estado')
            ->createQueryBuilder('e')                                
            ->where('e.nombre = ?1')
            ->setParameter(1, 'Desa')
            ->getQuery()
            ->getResult();                    

        $servicio[0]->setEstado($estado[0]);
        $servicio[0]->setIdEstado($estado[0]->getId());                

        $em = $this->getDoctrine()->getManager();
        $em->persist($servicio[0]);
        $em->flush();

        // Guardar el historial del cambio de estado               
        $fechaTerminado=new\DateTime('now');            
        $historial = new Historial();

        $historial->setServicio($servicio[0]);                     
        $historial->setIdServicio($servicio[0]->getId());
        $historial->setEstado($estado[0]);
        $historial->setIdEstado($estado[0]->getId());            
        $historial->setInicio($fechaTerminado);                   

        $em = $this->getDoctrine()->getManager();
        $em->persist($historial);
        $em->flush();

 
        $this->addFlash(
            'notice',
            'El servicio '.$servicio[0]->getCodigoInterno().' de tipo Mantención ha sido asignado al área de desarrollo.'
        );   
                
        //return $this->render('MonitorBundle:incidencia:index.html.twig');                         
        return $this->redirectToRoute('servicio_index');
    }    
        
    /**
     * Displays a form to edit an existing Servicio entity.
     *
     */
    public function testAction($id)
    {                                           
        
        $em = $this->getDoctrine()->getManager();
        
        $servicio= $em->getRepository('MonitorBundle:Servicio')
            ->createQueryBuilder('s')                                
            ->where('s.id = ?1')
            ->setParameter(1, $id)
            ->getQuery()
            ->getResult();                            
                
        $estado= $em->getRepository('MonitorBundle:Estado')
            ->createQueryBuilder('e')                                
            ->where('e.nombre = ?1')
            ->setParameter(1, 'Test')
            ->getQuery()
            ->getResult();                    

        $servicio[0]->setEstado($estado[0]);
        $servicio[0]->setIdEstado($estado[0]->getId());                

        $em = $this->getDoctrine()->getManager();
        $em->persist($servicio[0]);
        $em->flush();

        // Guardar el historial del cambio de estado               
        $fechaTerminado=new\DateTime('now');            
        $historial = new Historial();

        $historial->setServicio($servicio[0]);                     
        $historial->setIdServicio($servicio[0]->getId());
        $historial->setEstado($estado[0]);
        $historial->setIdEstado($estado[0]->getId());            
        $historial->setInicio($fechaTerminado);                   

        $em = $this->getDoctrine()->getManager();
        $em->persist($historial);
        $em->flush();

 
        $this->addFlash(
            'notice',
            'El servicio '.$servicio[0]->getCodigoInterno().' de tipo Mantención ha sido asignado al área de testing.'
        );   
                
        //return $this->render('MonitorBundle:incidencia:index.html.twig');                         
        return $this->redirectToRoute('servicio_index');
    }
    
    /**
     * Displays a form to edit an existing Servicio entity.
     *
     */
    public function papAction($id)
    {                                           
        
        $em = $this->getDoctrine()->getManager();
        
        $servicio= $em->getRepository('MonitorBundle:Servicio')
            ->createQueryBuilder('s')                                
            ->where('s.id = ?1')
            ->setParameter(1, $id)
            ->getQuery()
            ->getResult();                            
                
        $estado= $em->getRepository('MonitorBundle:Estado')
            ->createQueryBuilder('e')                                
            ->where('e.nombre = ?1')
            ->setParameter(1, 'PaP')
            ->getQuery()
            ->getResult();                    

        $servicio[0]->setEstado($estado[0]);
        $servicio[0]->setIdEstado($estado[0]->getId());                

        $em = $this->getDoctrine()->getManager();
        $em->persist($servicio[0]);
        $em->flush();

        // Guardar el historial del cambio de estado               
        $fechaTerminado=new\DateTime('now');            
        $historial = new Historial();

        $historial->setServicio($servicio[0]);                     
        $historial->setIdServicio($servicio[0]->getId());
        $historial->setEstado($estado[0]);
        $historial->setIdEstado($estado[0]->getId());            
        $historial->setInicio($fechaTerminado);                   

        $em = $this->getDoctrine()->getManager();
        $em->persist($historial);
        $em->flush();

 
        $this->addFlash(
            'notice',
            'El servicio '.$servicio[0]->getCodigoInterno().' de tipo Mantención ha sido agregado a la cola de servicios pendientes por PaP.| Todos los servicios pendientes por PaP pueden ser finalizados en el panel principal.'
        );   
                
        return $this->redirectToRoute('servicio_index');
        //return $this->render('MonitorBundle:incidencia:index.html.twig');                         
    }        
    
    /**
     * Displays a form to edit an existing Servicio entity.
     *
     */
    public function completeAction()
    {                                           
        
        $em = $this->getDoctrine()->getManager();
        
        $fechaTerminado=new\DateTime('now');      
        
        $servicios= $em->getRepository('MonitorBundle:Servicio')
            ->createQueryBuilder('s')                             
            ->join('s.estado', 'e')            
            ->where('e.nombre = ?1')
            ->setParameter(1, 'PaP')
            ->getQuery()
            ->getResult();                            
                
        $estado= $em->getRepository('MonitorBundle:Estado')
            ->createQueryBuilder('e')                                
            ->where('e.nombre = ?1')
            ->setParameter(1, 'Cerrada')
            ->getQuery()
            ->getResult();        
        
        foreach($servicios as $servicio){
            $servicio->setEstado($estado[0]);
            $servicio->setIdEstado($estado[0]->getId());                
            $servicio->setFechaSalida($fechaTerminado);

            $em = $this->getDoctrine()->getManager();
            $em->persist($servicio);
            $em->flush();

            // Guardar el historial del cambio de estado               
            $fechaInicio=new\DateTime('now');            
            $historial = new Historial();

            $historial->setServicio($servicio);                     
            $historial->setIdServicio($servicio->getId());
            $historial->setEstado($estado[0]);
            $historial->setIdEstado($estado[0]->getId());            
            $historial->setInicio($fechaInicio);                   

            $em = $this->getDoctrine()->getManager();
            $em->persist($historial);
            $em->flush();    
        }        
 
        $this->addFlash(
            'notice',
            'Todos los servicios en la cola de PaP han sido finalizados.| Estos pueden ser visualizados mediante el filtro finalizados en el panel principal.'
        );   
                
        //return $this->render('MonitorBundle:incidencia:index.html.twig');                         
        return $this->redirectToRoute('servicio_index');
    }            
    
    

    /**
     * Deletes a Servicio entity.
     *
     */
    public function deleteAction(Request $request, Servicio $servicio)
    {
        $form = $this->createDeleteForm($servicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($servicio);
            $em->flush();
        }

        return $this->redirectToRoute('servicio_index');
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
        
        if (!preg_match('{^[1-9][0-9]*}',$numeroTicket)){ 
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
                $message = 'Ya existe un servicio con este código';                
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
                ->join('i.origenIncidencia', 'o')
                ->join('i.estadoIncidencia', 'e')                                                
                ->where('YEAR(i.fechaReporte) = ?1')
                ->andWhere('MONTH(i.fechaReporte) = ?2')
                ->andWhere('e.nombre in (?3)');
        
        $parameters[1] = $anio;
        
        $parameters[2] = $mes;
        
        if($estado == 1)
            $parameters[3]=['En Cola','En Gestión FONASA','Pendiente MT'];
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
                
        
        $body = array();              
        $cont = 0;                
        
        foreach($incidencias as $incidencia){                        
            
            $fila = array();  
            
            array_push($fila,$incidencia->getNumeroTicket());
            array_push($fila,$incidencia->getFechaReporte()->format('d/m/Y H:i'));
            //array_push($fila,$servicio->getComponente()->getNombre());
            array_push($fila,$incidencia->getOrigenIncidencia()->getNombre());
            //array_push($fila,$servicio->getTipoServicio()->getTipo()->getNombre());
            array_push($fila,$incidencia->getCategoriaIncidencia()->getNombre());
            array_push($fila,$incidencia->getComponente()->getNombre());
            //array_push($fila,$incidencia->getPrioridad()->getNombre());                                                                                    
                        
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
