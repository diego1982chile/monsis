<?php

namespace Fonasa\MonitorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Fonasa\MonitorBundle\Entity\Incidencia;
use Fonasa\MonitorBundle\Entity\HistorialIncidencia;
use Fonasa\MonitorBundle\Entity\EstadoIncidencia;
use Fonasa\MonitorBundle\Entity\ObservacionIncidencia;
use Fonasa\MonitorBundle\Entity\DocumentoIncidencia;

use Fonasa\MonitorBundle\Form\IncidenciaType;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

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
        
        $session = $request->getSession();
        
        //die(json_encode($request->get('incidencia')));
        
        // dummy code - this is here just so that the Task has some tags
        // otherwise, this isn't an interesting example
        /*
        $documentoIncidencia1 = new DocumentoIncidencia();
        $documentoIncidencia1->setNombre('documentoIncidencia1');
        $incidencia->getDocumentosIncidencia()->add($documentoIncidencia1);                 
         */
        // end dummy code
        
        $em = $this->getDoctrine()->getManager();        
        
        $form = $this->createForm('Fonasa\MonitorBundle\Form\IncidenciaType', $incidencia, array('filtroComponente' => $session->get('filtroComponente')));
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
            $incidencia->setTocada($fechaIngreso);
            
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
                               
            $em = $this->getDoctrine()->getManager();
            $em->persist($incidencia);                                    
            //$em->flush();
            
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            foreach($incidencia->getDocumentosIncidencia() as $key => $documentoIncidencia){
                
                $file = $documentoIncidencia->getArchivo();

                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                //$brochuresDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads';
                $brochuresDir = $this->container->getParameter('kernel.root_dir').'/../web/bundles/monsis/uploads';
                
                $file->move($brochuresDir, $fileName);
                
                $documentoIncidencia->setNombre(str_replace(' ','_',$documentoIncidencia->getTipoDocumentoIncidencia()->getNombre()).
                                                '_Ticket_'.$incidencia->getNumeroTicket().'_'.($key+1));
                
                $documentoIncidencia->setArchivo($fileName);
                                
                $documentoIncidencia->setIncidencia($incidencia);
                $documentoIncidencia->setIdIncidencia($incidencia->getId());
                                
                $em->persist($documentoIncidencia);                                                    
            }            
            
            foreach($incidencia->getComentariosIncidencia() as $key => $comentarioIncidencia){
                                                                
                $comentarioIncidencia->setIncidencia($incidencia);
                $comentarioIncidencia->setIdIncidencia($incidencia->getId());
                                
                $em->persist($comentarioIncidencia);                                                    
            }   
            
            $historialIncidencia = new HistorialIncidencia();
            
            $historialIncidencia->setInicio($fechaIngreso);
            $historialIncidencia->setObservacion('Se inicia la incidencia N°Ticket '.$numeroTicket);            
            $historialIncidencia->setEstadoIncidencia($estado[0]);
            $historialIncidencia->setIdEstadoIncidencia($estado[0]->getId());            
            $historialIncidencia->setIncidencia($incidencia);
            $historialIncidencia->setIdIncidencia($incidencia->getId());
            $historialIncidencia->setUsuario($incidencia->getUsuario()->getUsername());        
            
            $em->persist($historialIncidencia);
                                    
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

    public function historialAction(Request $request){
        
        $em = $this->getDoctrine()->getManager();              
        
        $id= $request->query->get('id');                
        
        $incidencia = $em->getRepository('MonitorBundle:Incidencia')->find($id);
        
        $historial = array();
        
        foreach($incidencia->getHistorialesIncidencia() as $historialIncidencia){
            
            $fila = array();
            
            array_push($fila,$historialIncidencia->getInicio()->format('d/m/Y H:i'));
            array_push($fila,$historialIncidencia->getEstadoIncidencia()->getNombre());
            array_push($fila,$historialIncidencia->getUsuario()==null?'-':$historialIncidencia->getUsuario());                        
            array_push($fila,$historialIncidencia->getObservacion());            
            
            $historial[] = $fila;
        }
        
        return new JsonResponse($historial);        
    }
    
    public function comentarioAction(Request $request){
        
        $em = $this->getDoctrine()->getManager();              
        
        $id= $request->query->get('id');                
        
        $incidencia = $em->getRepository('MonitorBundle:Incidencia')->find($id);
        
        $comentario = array();
        
        foreach($incidencia->getComentariosIncidencia() as $comentarioIncidencia){
            
            $fila = array();
            
            array_push($fila,$comentarioIncidencia->getComentario());
            
            $comentario[] = $fila;
        }
        
        return new JsonResponse($comentario);        
    }

    /**
     * Finds and displays a Incidencia entity.
     *
     */
    public function showAction(Incidencia $incidencia)
    {                        
        $deleteForm = $this->createDeleteForm($incidencia);
        
        $em = $this->getDoctrine()->getManager();                 
        
        $items = array();
        
        foreach($incidencia->getHistorialesIncidencia() as $historialIncidencia){
            $item['type'] = 'smallItem';
            $item['label'] = $historialIncidencia->getInicio()->format('d/m/Y H:i').'<br><i>'.$historialIncidencia->getEstadoIncidencia()->getNombre().'</i> (<small>'.$historialIncidencia->getUsuario().'</small>)';
            $item['relativePosition'] = $historialIncidencia->getInicio();
            $item['shortContent'] = '<i><small>'.$historialIncidencia->getObservacion().'</small></i>';
            
            $items[] = $item;
        }
        
        $fillRatio = intval(100*$incidencia->getHhEfectivas()/$incidencia->getSeveridad()->getSla());                        
            
        $color='';

        if(80<$fillRatio && $fillRatio<=100)
            $color='orange';
        if($fillRatio>100)
            $color='red';                                                    
        if($incidencia->getEstadoIncidencia()->getNombre()=='Resuelta MT')
            $color='green';                            
        
        $title='(HH_Efectivas/SLA)%';

        $html='<div title='.$title.' class="c100 p'.min($fillRatio,100).' center big '.$color.'"><span>'.$fillRatio.'%</span><div class="slice"><div class="bar"></div><div class="fill"></div></div></div>';

        return $this->render('MonitorBundle:incidencia:show.html.twig', array(
            'incidencia' => $incidencia,
            'delete_form' => $deleteForm->createView(),
            'eventos' => json_encode($items),
            'fill_ratio' => $html
        ));
    }
    
    /**
     * Displays a form to edit an existing Servicio entity.
     *
     */
    public function editAction(Request $request, Incidencia $incidencia)
    {
        //die(json_encode($request->request->get('incidencia')));
        
        $em = $this->getDoctrine()->getManager();
                
        $deleteForm = $this->createDeleteForm($incidencia);
        
        $session = $request->getSession();
        
        $editForm = $this->createForm('Fonasa\MonitorBundle\Form\IncidenciaType', $incidencia, array('filtroComponente' => $session->get('filtroComponente')));                        
        
        $editForm->handleRequest($request);
                              
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $incidencia->setTocada(new\DateTime('now'));
                        
            $em->persist($incidencia);
            //$em->flush();            
            
            foreach($incidencia->getComentariosIncidencia() as $comentarioIncidencia){            
                                                                                                
                if ($comentarioIncidencia->getComentario() == 'eliminado') {
                    
                    // remove the Task from the Tag                    
                    $incidencia->removeComentariosIncidencia($comentarioIncidencia);                    

                    // if it was a many-to-one relationship, remove the relationship like this                                
                    $em->remove($comentarioIncidencia);
                    $em->persist($incidencia);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $em->remove($tag);
                } 
                else{                    
                    //if($tipo != 'string'){
                               
                    if($comentarioIncidencia->getId() == null){                        
                        // Si el tipo es file es un archivo nuevo                                                                    

                        $comentarioIncidencia->setIncidencia($incidencia);
                        $comentarioIncidencia->setIdIncidencia($incidencia->getId());                                                
                        $incidencia->addComentariosIncidencia($comentarioIncidencia);
                        
                        $em->persist($incidencia);  
                        
                        $em->persist($comentarioIncidencia);                                                                          
                    }                                                                                                                          
                }                
            }
            
            foreach($incidencia->getDocumentosIncidencia() as $key => $documentoIncidencia){            
                                                                                
                $file = $documentoIncidencia->getArchivo();
                
                $tipo = gettype($file);                                                            
                
                if ($documentoIncidencia->getNombre() == 'eliminado') {
                    // Eliminar archivo del repositorio
                    $path = $this->container->getParameter('kernel.root_dir').'/../web/bundles/monsis/uploads/';
                    //$fs = new Filesystem();
                    
                    unlink($path.$file);
                    /*
                    try {
                        $fs->remove(array('symlink', $path, $file));
                    } catch (IOExceptionInterface $e) {
                        echo "Ha ocurrido un error mientras se eliminaba el archivo ".$documentoIncidencia->getNombre();
                    }                            
                    */
                    // remove the Task from the Tag                    
                    $incidencia->removeDocumentosIncidencia($documentoIncidencia);                    

                    // if it was a many-to-one relationship, remove the relationship like this                                
                    $em->remove($documentoIncidencia);
                    $em->persist($incidencia);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $em->remove($tag);
                } 
                /*
                elseif($tipo == 'string' && $documentoIncidencia->getNombre() != 'eliminado'){
                    //No hacer nada
                }                
                */
                else{                    
                    //if($tipo != 'string'){
                               
                    if($documentoIncidencia->getId() == null){                        
                        // Si el tipo es file es un archivo nuevo                    

                        // Generate a unique name for the file before saving it
                        $fileName = md5(uniqid()).'.'.$file->guessExtension();

                        // Move the file to the directory where brochures are stored
                        //$brochuresDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads';
                        $brochuresDir = $this->container->getParameter('kernel.root_dir').'/../web/bundles/monsis/uploads';

                        $file->move($brochuresDir, $fileName);

                        $documentoIncidencia->setNombre(str_replace(' ','_',$documentoIncidencia->getTipoDocumentoIncidencia()->getNombre()).
                                                        '_Ticket_'.$incidencia->getNumeroTicket().'_'.(count($incidencia->getDocumentosIncidencia())));
                        
                        //$documentoIncidencia->setNombre('algo'+$key);
                        
                        $documentoIncidencia->setArchivo($fileName);
                        
                        //die($documentoIncidencia->getNombre());

                        $documentoIncidencia->setIncidencia($incidencia);
                        $documentoIncidencia->setIdIncidencia($incidencia->getId());                                                
                        $incidencia->addDocumentosIncidencia($documentoIncidencia);
                        
                        $em->persist($incidencia);  
                        
                        $em->persist($documentoIncidencia);                                                                          
                    }                                                                                                                          
                }                
            }            
            
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
    public function statusAction($id, $status, $usuario, $observacion)
    {                                            
        //$id= $request->request->get('id');
        //$status= $request->request->get('status');
        //die($observacion);
        
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
        
        $usuarios= $em->getRepository('MonitorBundle:Usuario')
            ->createQueryBuilder('u')                                
            ->where('u.username = ?1')
            ->setParameter(1, $usuario)
            ->getQuery()
            ->getResult();   
        
        if(sizeof($usuarios)){                        
            $incidencia[0]->setUsuario($usuarios[0]);
            $incidencia[0]->setIdUsuario($usuarios[0]->getId());                
        }

        $incidencia[0]->setEstadoIncidencia($estado[0]);
        $incidencia[0]->setIdEstadoIncidencia($estado[0]->getId());                
        
        $incidencia[0]->setTocada(new\DateTime('now'));                                
        
        switch ($status){
            case 'En Cola': // Si se deja en cola, la fecha de inicio salida se anulan
                //$incidencia[0]->setFechaInicio(null);
                //$incidencia[0]->setFechaSalida(null
                $incidencia[0]->setFechaSalida(null);
                $incidencia[0]->setUsuario(null);
                break;
            case 'En Gestión FONASA': // Si se deja en gestión FONASA, no se hace nada
                $incidencia[0]->setFechaSalida(null);
                $incidencia[0]->setUsuario(null);
                break;            
            case 'Pendiente MT': // Si se deja Pendiente MT se actualiza la fecha inicio
                if($incidencia[0]->getFechaInicio() == null)
                    $incidencia[0]->setFechaInicio(new\DateTime('now'));
                $incidencia[0]->setFechaSalida(null);
                //$incidencia[0]->setHhEfectivas(0);
                $incidencia[0]->setFechaUltHH(new\DateTime('now'));
                break;        
            case 'Resuelta MT':
                // Si se deja como resuelta, agregar observaciones...
                $incidencia[0]->setFechaSalida(new\DateTime('now'));                                                               
                $incidencia[0]->setUsuario(null);
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
        $historial->setObservacion($observacion);  
        if(sizeof($usuarios))   
            $historial->setUsuario($usuarios[0]->getUsername());        
        
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

            $this->addFlash(
                'notice',
                'Se ha eliminado la incidencia N°Ticket '.$incidencia->getNumeroTicket().'.'
            );
        }

        return $this->redirectToRoute('incidencia_index');
    }
    
/**
     * Deletes a Incidencia entity.
     *
     */
    public function deleteDocumentoAction(Request $request)
    {        
        $archivo= $request->request->get('archivo');
        
        $em = $this->getDoctrine()->getManager();                    
        $error = false;
        $message = 'OK';
        
        //Si se esta editando o asignando un servicio se debe proveer el id del servicio        
        $documentoIncidencia= $em->getRepository('MonitorBundle:DocumentoIncidencia')
                                 ->createQueryBuilder('di')                                
                                 ->where('di.archivo = ?1')                                 
                                 ->setParameter(1, $archivo)                                                     
                                 ->getQuery()
                                 ->getResult(); 
        
        if(empty($documentoIncidencia)){
            $error=true;
            $message='No existe el archivo';
        }
        
        
        /*
        $incidencia = $documentoIncidencia[0]->getIncidencia();        
        $incidencia->removeDocumentosIncidencia($documentoIncidencia[0]);
                        
        $em->persist($incidencia);
        
        //$em->remove($documentoIncidencia[0]);
        $em->flush();        
        */
        
        return new JsonResponse(array('error' => $error, 'message' => $message));                
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
        // Si es numerico, validar que sea entero
        if (floor($numeroTicket)!=$numeroTicket){            
            $error = true;
            $message = 'N°Ticket no válido';            
        }
        // Si es entero, validar que no empiece con 0
        if(substr($numeroTicket, 0, 1) == '0'){
            $error = true;
            $message = 'N°Ticket no válido';
        }        
        // Si es numerico, validar que sea entero positivo menor a un maximo
        if($numeroTicket<=0 || $numeroTicket>999999999){
            $error = true;
            $message = 'N°Ticket no válido';                        
        }
        
        if(!$error){
            $em = $this->getDoctrine()->getManager();                    
            //Si se esta editando o asignando un servicio se debe proveer el id del servicio
            if($id != null){                
                $incidencia= $em->getRepository('MonitorBundle:Incidencia')
                ->createQueryBuilder('i')                                
                ->where('i.numeroTicket = ?1')
                ->andWhere('i.id <> ?2')
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
        $iDisplayStart= $request->query->get('iDisplayStart');      
        $iDisplayLength= $request->query->get('iDisplayLength');      
        ////////////////////////////////////
        
        //Obtener parámetros de filtros
        $componente= $request->query->get('componente');
        $anio= $request->query->get('anio');
        $mes= $request->query->get('mes');
        $estado= $request->query->get('estado');                
        $resetFiltros = $request->query->get('resetFiltros');                
        //////////////////                                
        
        $em = $this->getDoctrine()->getManager();                
        
        $parameters = array();                

        $qb = $em->getRepository('MonitorBundle:Incidencia')
                ->createQueryBuilder('i')                
                ->join('i.categoriaIncidencia', 'ci')
                ->join('i.componente', 'c')
                ->join('i.severidad', 's')
                ->join('i.estadoIncidencia', 'e');                                                
                //->where('YEAR(i.fechaInicio) = ?1')
                //->andWhere('MONTH(i.fechaInicio) = ?2');
                        
        if($componente != -1){
            $qb->andWhere('c.id = ?3');
            $parameters[3] = $componente;
        }
        
        if($estado != -1){
            $qb->andWhere('e.nombre in (?4)');                
        }
        
        // Si se estan consultando las incidencias resueltas agregar filtro por mes y año
        if($estado == 2){            
            $qb->andWhere('YEAR(i.fechaSalida) = ?1');                
            $qb->andWhere('MONTH(i.fechaSalida) = ?2');                
            $parameters[1] = $anio;        
            $parameters[2] = $mes;
                    
        }
                        
        switch($estado){
            case 1:
                $parameters[4]=[/*'En Cola',*/'En Gestión FONASA','Pendiente MT'];
                break;
            case 2:
                $parameters[4]=['Resuelta MT'];
                break;
        }                            
                    
        if($sSearch != null){            
            $qb->andWhere(
            $qb->expr()->orx(
            $qb->expr()->like('i.numeroTicket', '?5'),                    
            $qb->expr()->like('ci.nombre', '?5'),
            $qb->expr()->like('c.nombre', '?5'),
            $qb->expr()->like('s.nombre', '?5'),            
            $qb->expr()->like('e.nombre', '?5')
           ));
            
           $parameters[5]='%'.$sSearch.'%'; 
        }
        
        if($iSortCol != null){
            
            switch($iSortCol){
                case '7':
                    $qb->orderBy('i.tocada', 'DESC');
                    break;
                case '1':
                    $qb->orderBy('i.numeroTicket', $sSortDir);
                    break;
                case '2':                    
                    $qb->orderBy('i.fechaInicio', $sSortDir);
                    break;                
                case '3':
                    $qb->orderBy('i.fechaSalida', $sSortDir);
                    break;  
                case '4':
                    $qb->orderBy('s.nombre', $sSortDir);
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
        
        foreach($incidencias as $key=>$incidencia){                        
                        
            if($iDisplayLength != -1){
                if($key < $iDisplayStart || $key >= $iDisplayStart+$iDisplayLength)
                    continue;
            }
            
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
            
            //$color;
            
            /*
            switch($incidencia->getEstadoIncidencia()->getNombre()){
                case 'En Gestión FONASA':
                    $color='#F5A9A9';
                    break;
                case 'Pendiente MT':
                    $color='yellow';
                    break;
                case 'Resuelta MT':
                    $color='#A5DF00';
                    break;
            }
            */
                        
            $html='<a href="'.$this->generateUrl('incidencia_show', array('id' => $incidencia->getId())).'">Ticket'.$incidencia->getNumeroTicket().'</a>';
            
            array_push($fila,$html);
            array_push($fila,$incidencia->getFechaInicio()==null?'-':$incidencia->getFechaInicio()->format('d/m/Y H:i'));
            array_push($fila,$incidencia->getFechaSalida()==null?'-':$incidencia->getFechaSalida()->format('d/m/Y H:i'));            
            //array_push($fila,$servicio->getComponente()->getNombre());
            array_push($fila,$incidencia->getSeveridad()->getNombre());
            //array_push($fila,$servicio->getTipoServicio()->getTipo()->getNombre());            
            //array_push($fila,$incidencia->getPrioridad()->getNombre());                                      
            
            $html='<div class="dropdown" style="position:relative">';
            $html=$html.'<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$incidencia->getEstadoIncidencia()->getNombre().'<span class="caret"></span></a>';
            
            if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
                
                $html=$html.'<ul class="dropdown-menu">';                                    
            
                foreach($estados as $estado)                        
                {                                                          
                    $usuarios = $em->getRepository('MonitorBundle:Usuario')
                    ->createQueryBuilder('u')                                
                    ->join('u.estadoIncidencia','em')
                    ->where('em.nombre = ?1')
                    ->setParameter(1, $estado->getNombre())
                    ->getQuery()
                    ->getResult();

                    $active="";                    

                    if(sizeof($usuarios)){                                        

                        if($incidencia->getEstadoIncidencia()->getNombre()==$estado->getNombre())
                            $active="active";

                        $html=$html.'<li class="'.$active.'">';   
                        $html=$html.'<a class="trigger right-caret">'.$estado->getNombre().'</a>';
                        $html=$html.'<ul class="dropdown-menu sub-menu">';

                        foreach($usuarios as $usuario){     
                            $active2="";
                                if($incidencia->getUsuario()!=null){
                                    if($incidencia->getUsuario()->getUsername()==$usuario->getUsername())                            
                                        $active2="active";
                                }
                                $html=$html.'<li class="'.$active2.'"><a class="estados" href="'.$this->generateUrl('incidencia_status', array('id' => $incidencia->getId(), 'status' => $estado->getNombre(), 'usuario' => $usuario->getUsername(), 'observacion' => 'null')).'">'.$usuario->getUsername().'</a></li>';                            

                        }
                        $html=$html.'</ul>';
                    }   
                    else{                    
                        if($incidencia->getEstadoIncidencia()->getNombre()==$estado->getNombre())
                            $active="active";
                        $html=$html.'<li class="'.$active.'"><a class="estados" href="'.$this->generateUrl('incidencia_status', array('id' => $incidencia->getId(), 'status' => $estado->getNombre(), 'usuario' => 'null', 'observacion' => 'null')).'">'.$estado->getNombre().'</a></li>';        
                    }
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
            
            $html='<div class="btn-group">';
            $html=$html.'<a href="#" class="btn btn-default ver_historial" id='.$incidencia->getId().'><i class="fa fa-list"></i></a>';
            
            if(count($incidencia->getComentariosIncidencia())>0)
                $html=$html.'<a href="#" class="btn btn-default ver_comentario" id='.$incidencia->getId().'><i class="fa fa-comment-o"></i></a>';

            $html=$html.'</div>';
            
            /*
            $html='<ul><li><a href="'.$this->generateUrl('incidencia_show', array('id' => $incidencia->getId())).'">ver</a></li>';
            
            if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))                
                $html=$html.'<li><a href="'.$this->generateUrl('incidencia_edit', array('id' => $incidencia->getId())).'">editar</a></li></ul>';            
            */
            array_push($fila,$html);
            
            //Se añade campo tocada
            array_push($fila, $incidencia->getTocada());
            
            array_push($body, $fila);            
        }
                
        $output= array(
          'sEcho' => intval($request->request->get('sEcho')),
          'iTotalRecords' => sizeof($incidencias),
          'iTotalDisplayRecords' => sizeof($incidencias),  
          'aaData' => $body          
        );
        
        return new JsonResponse($output);
    }
}
