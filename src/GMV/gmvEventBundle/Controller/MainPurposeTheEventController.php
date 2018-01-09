<?php

namespace GMV\gmvEventBundle\Controller;

use GMV\gmvEventBundle\Entity\MainPurposeTheEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Mainpurposetheevent controller.
 *
 * @Route("mainpurposetheevent")
 */
class MainPurposeTheEventController extends Controller
{
    /**
     * Lists all mainPurposeTheEvent entities.
     *
     * @Route("/", name="mainpurposetheevent_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $mainPurposeTheEvents = $em->getRepository('EventBundle:MainPurposeTheEvent')->findAll();

        return $this->render('mainpurposetheevent/index.html.twig', array(
            'mainPurposeTheEvents' => $mainPurposeTheEvents,
        ));
    }

    /**
     * Creates a new mainPurposeTheEvent entity.
     *
     * @Route("/new", name="mainpurposetheevent_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $mainPurposeTheEvent = new Mainpurposetheevent();
        $form = $this->createForm('GMV\gmvEventBundle\Form\MainPurposeTheEventType', $mainPurposeTheEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mainPurposeTheEvent);
            $em->flush();

            return $this->redirectToRoute('mainpurposetheevent_show', array('id' => $mainPurposeTheEvent->getId()));
        }

        return $this->render('mainpurposetheevent/new.html.twig', array(
            'mainPurposeTheEvent' => $mainPurposeTheEvent,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a mainPurposeTheEvent entity.
     *
     * @Route("/{id}", name="mainpurposetheevent_show")
     * @Method("GET")
     */
    public function showAction(MainPurposeTheEvent $mainPurposeTheEvent)
    {
        $deleteForm = $this->createDeleteForm($mainPurposeTheEvent);

        return $this->render('mainpurposetheevent/show.html.twig', array(
            'mainPurposeTheEvent' => $mainPurposeTheEvent,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing mainPurposeTheEvent entity.
     *
     * @Route("/{id}/edit", name="mainpurposetheevent_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MainPurposeTheEvent $mainPurposeTheEvent)
    {
        $deleteForm = $this->createDeleteForm($mainPurposeTheEvent);
        $editForm = $this->createForm('GMV\gmvEventBundle\Form\MainPurposeTheEventType', $mainPurposeTheEvent);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mainpurposetheevent_edit', array('id' => $mainPurposeTheEvent->getId()));
        }

        return $this->render('mainpurposetheevent/edit.html.twig', array(
            'mainPurposeTheEvent' => $mainPurposeTheEvent,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a mainPurposeTheEvent entity.
     *
     * @Route("/{id}", name="mainpurposetheevent_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MainPurposeTheEvent $mainPurposeTheEvent)
    {
        $form = $this->createDeleteForm($mainPurposeTheEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($mainPurposeTheEvent);
            $em->flush();
        }

        return $this->redirectToRoute('mainpurposetheevent_index');
    }

    /**
     * Creates a form to delete a mainPurposeTheEvent entity.
     *
     * @param MainPurposeTheEvent $mainPurposeTheEvent The mainPurposeTheEvent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MainPurposeTheEvent $mainPurposeTheEvent)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('mainpurposetheevent_delete', array('id' => $mainPurposeTheEvent->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
