<?php

namespace GMV\gmvEventBundle\Controller;

use GMV\gmvEventBundle\Entity\TypeActivityEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Typeactivityevent controller.
 *
 * @Route("typeactivityevents")
 */
class TypeActivityEventsController extends Controller
{
    /**
     * Lists all typeActivityEvent entities.
     *
     * @Route("/", name="typeactivityevents_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typeActivityEvents = $em->getRepository('EventBundle:TypeActivityEvents')->findAll();

        return $this->render('typeactivityevents/index.html.twig', array(
            'typeActivityEvents' => $typeActivityEvents,
        ));
    }

    /**
     * Creates a new typeActivityEvent entity.
     *
     * @Route("/new", name="typeactivityevents_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeActivityEvent = new Typeactivityevent();
        $form = $this->createForm('GMV\gmvEventBundle\Form\TypeActivityEventsType', $typeActivityEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeActivityEvent);
            $em->flush();

            return $this->redirectToRoute('typeactivityevents_show', array('id' => $typeActivityEvent->getId()));
        }

        return $this->render('typeactivityevents/new.html.twig', array(
            'typeActivityEvent' => $typeActivityEvent,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typeActivityEvent entity.
     *
     * @Route("/{id}", name="typeactivityevents_show")
     * @Method("GET")
     */
    public function showAction(TypeActivityEvents $typeActivityEvent)
    {
        $deleteForm = $this->createDeleteForm($typeActivityEvent);

        return $this->render('typeactivityevents/show.html.twig', array(
            'typeActivityEvent' => $typeActivityEvent,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typeActivityEvent entity.
     *
     * @Route("/{id}/edit", name="typeactivityevents_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeActivityEvents $typeActivityEvent)
    {
        $deleteForm = $this->createDeleteForm($typeActivityEvent);
        $editForm = $this->createForm('GMV\gmvEventBundle\Form\TypeActivityEventsType', $typeActivityEvent);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('typeactivityevents_edit', array('id' => $typeActivityEvent->getId()));
        }

        return $this->render('typeactivityevents/edit.html.twig', array(
            'typeActivityEvent' => $typeActivityEvent,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typeActivityEvent entity.
     *
     * @Route("/{id}", name="typeactivityevents_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypeActivityEvents $typeActivityEvent)
    {
        $form = $this->createDeleteForm($typeActivityEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeActivityEvent);
            $em->flush();
        }

        return $this->redirectToRoute('typeactivityevents_index');
    }

    /**
     * Creates a form to delete a typeActivityEvent entity.
     *
     * @param TypeActivityEvents $typeActivityEvent The typeActivityEvent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeActivityEvents $typeActivityEvent)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typeactivityevents_delete', array('id' => $typeActivityEvent->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
