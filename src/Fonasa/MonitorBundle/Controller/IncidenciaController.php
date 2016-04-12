<?php

namespace Fonasa\MonitorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Fonasa\MonitorBundle\Entity\Incidencia;
use Fonasa\MonitorBundle\Form\IncidenciaType;

/**
 * Incidencia controller.
 *
 */
class IncidenciaController extends Controller
{
    /**
     * Lists all Incidencia entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $incidencias = $em->getRepository('MonitorBundle:Incidencia')->findAll();

        return $this->render('incidencia/index.html.twig', array(
            'incidencias' => $incidencias,
        ));
    }

    /**
     * Creates a new Incidencia entity.
     *
     */
    public function newAction(Request $request)
    {
        $incidencium = new Incidencia();
        $form = $this->createForm('Fonasa\MonitorBundle\Form\IncidenciaType', $incidencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($incidencium);
            $em->flush();

            return $this->redirectToRoute('incidencia_show', array('id' => $incidencium->getId()));
        }

        return $this->render('incidencia/new.html.twig', array(
            'incidencium' => $incidencium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Incidencia entity.
     *
     */
    public function showAction(Incidencia $incidencium)
    {
        $deleteForm = $this->createDeleteForm($incidencium);

        return $this->render('incidencia/show.html.twig', array(
            'incidencium' => $incidencium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Incidencia entity.
     *
     */
    public function editAction(Request $request, Incidencia $incidencium)
    {
        $deleteForm = $this->createDeleteForm($incidencium);
        $editForm = $this->createForm('Fonasa\MonitorBundle\Form\IncidenciaType', $incidencium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($incidencium);
            $em->flush();

            return $this->redirectToRoute('incidencia_edit', array('id' => $incidencium->getId()));
        }

        return $this->render('incidencia/edit.html.twig', array(
            'incidencium' => $incidencium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Incidencia entity.
     *
     */
    public function deleteAction(Request $request, Incidencia $incidencium)
    {
        $form = $this->createDeleteForm($incidencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($incidencium);
            $em->flush();
        }

        return $this->redirectToRoute('incidencia_index');
    }

    /**
     * Creates a form to delete a Incidencia entity.
     *
     * @param Incidencia $incidencium The Incidencia entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Incidencia $incidencium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('incidencia_delete', array('id' => $incidencium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
