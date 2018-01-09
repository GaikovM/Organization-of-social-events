<?php

namespace GMV\gmvEventBundle\Controller;

use GMV\gmvEventBundle\Entity\TheTargetAudienceEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Thetargetaudienceevent controller.
 *
 * @Route("thetargetaudienceevents")
 */
class TheTargetAudienceEventsController extends Controller
{
    /**
     * Lists all theTargetAudienceEvent entities.
     *
     * @Route("/", name="thetargetaudienceevents_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $theTargetAudienceEvents = $em->getRepository('EventBundle:TheTargetAudienceEvents')->findAll();

        return $this->render('thetargetaudienceevents/index.html.twig', array(
            'theTargetAudienceEvents' => $theTargetAudienceEvents,
        ));
    }

    /**
     * Creates a new theTargetAudienceEvent entity.
     *
     * @Route("/new", name="thetargetaudienceevents_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $theTargetAudienceEvent = new Thetargetaudienceevent();
        $form = $this->createForm('GMV\gmvEventBundle\Form\TheTargetAudienceEventsType', $theTargetAudienceEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($theTargetAudienceEvent);
            $em->flush();

            return $this->redirectToRoute('thetargetaudienceevents_show', array('id' => $theTargetAudienceEvent->getId()));
        }

        return $this->render('thetargetaudienceevents/new.html.twig', array(
            'theTargetAudienceEvent' => $theTargetAudienceEvent,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a theTargetAudienceEvent entity.
     *
     * @Route("/{id}", name="thetargetaudienceevents_show")
     * @Method("GET")
     */
    public function showAction(TheTargetAudienceEvents $theTargetAudienceEvent)
    {
        $deleteForm = $this->createDeleteForm($theTargetAudienceEvent);

        return $this->render('thetargetaudienceevents/show.html.twig', array(
            'theTargetAudienceEvent' => $theTargetAudienceEvent,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing theTargetAudienceEvent entity.
     *
     * @Route("/{id}/edit", name="thetargetaudienceevents_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TheTargetAudienceEvents $theTargetAudienceEvent)
    {
        $deleteForm = $this->createDeleteForm($theTargetAudienceEvent);
        $editForm = $this->createForm('GMV\gmvEventBundle\Form\TheTargetAudienceEventsType', $theTargetAudienceEvent);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('thetargetaudienceevents_edit', array('id' => $theTargetAudienceEvent->getId()));
        }

        return $this->render('thetargetaudienceevents/edit.html.twig', array(
            'theTargetAudienceEvent' => $theTargetAudienceEvent,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a theTargetAudienceEvent entity.
     *
     * @Route("/{id}", name="thetargetaudienceevents_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TheTargetAudienceEvents $theTargetAudienceEvent)
    {
        $form = $this->createDeleteForm($theTargetAudienceEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($theTargetAudienceEvent);
            $em->flush();
        }

        return $this->redirectToRoute('thetargetaudienceevents_index');
    }

    /**
     * Creates a form to delete a theTargetAudienceEvent entity.
     *
     * @param TheTargetAudienceEvents $theTargetAudienceEvent The theTargetAudienceEvent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TheTargetAudienceEvents $theTargetAudienceEvent)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('thetargetaudienceevents_delete', array('id' => $theTargetAudienceEvent->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
