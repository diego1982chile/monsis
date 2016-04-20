<?php

namespace Fonasa\MonitorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Fonasa\MonitorBundle\Entity\Mantencion;
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
        
        $mantencion = new Mantencion();
        $form = $this->createForm('Fonasa\MonitorBundle\Form\MantencionType', $mantencion, array('origen' => 'Requerimiento'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $numeroRequerimiento=$request->request->get('mantencion')['numeroRequerimiento'];
            $inicioProgramado=$request->request->get('mantencion')['inicioProgramado'];
            
            $fechaIngreso=new\DateTime('now');
            
            if($inicioProgramado){
                $nomEstado='En cola';                            
                $msg='La mantención está en cola y tiene un inicio programado para el '.$request->request->get('incidencia')['fechaInicio'].', fecha en la que será automáticamente asignada al área de desarrollo.';
            }
            else{ //Si no es inicio programado setear la fecha de inicio
                $mantencion->setFechaInicio($fechaIngreso);
                $nomEstado='Desa';
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
            
            //$em = $this->getDoctrine()->getManager();
            $em->persist($mantencion);
            $em->flush();
            
            $this->addFlash(
                'notice',
                'Se ha ingresado una nueva mantención.| La mantención está asociada al número de requerimiento:'.$numeroRequerimiento.'.|'.$msg
            );               

            return $this->redirectToRoute('mantencion_show', array('id' => $mantencion->getId()));
        }

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

        return $this->render('mantencion/edit.html.twig', array(
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
        
        if (!preg_match('{^[1-9][0-9]*}',$numeroRequerimiento)){ 
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
}
