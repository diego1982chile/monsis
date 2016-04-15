<?php

namespace Fonasa\MonitorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Fonasa\MonitorBundle\Entity\Mantencion;
use Fonasa\MonitorBundle\Form\MantencionType;

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
        $mantencion = new Mantencion();
        $form = $this->createForm('Fonasa\MonitorBundle\Form\MantencionType', $mantencion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mantencion);
            $em->flush();

            return $this->redirectToRoute('mantencion_show', array('id' => $mantencion->getId()));
        }

        return $this->render('mantencion/new.html.twig', array(
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

        return $this->render('mantencion/show.html.twig', array(
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
}
