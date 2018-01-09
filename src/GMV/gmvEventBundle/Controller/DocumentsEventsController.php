<?php

namespace GMV\gmvEventBundle\Controller;

use GMV\gmvEventBundle\Entity\DocumentsEvents;
use GMV\gmvEventBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @Route("event")
 */
class DocumentsEventsController extends Controller
{
    /**
     * @Route("/doc/{id}", name="doc_events_index")
     * @Method("GET")
     */
    public function indexDocEvents($id)
    {

        if (empty($id)) {
            return $this->redirectToRoute('user_panel_index');
        }
        $em = $this->getDoctrine()->getManager();
        $Event = $em->getRepository('EventBundle:Event')->find($id);


        if (empty($Event) or ($Event->getUser() != $this->getUser())) {
            return $this->redirectToRoute('user_panel_index');
        }

        $em = $this->getDoctrine()->getManager();
        $docEvents = $em->getRepository('EventBundle:DocumentsEvents')->findAllDocEvent($id);

        return $this->render('documentsevents/index.html.twig', array(
            'documentsEvents' => $docEvents,
            'id_event' => $id,
            'event_name' => $Event->getName(),
        ));
    }

    /**
     * @Route("/doc/new/", name="doc_events_new")
     * @Method({"GET", "POST"})
     */
    public function newDocEvents(Request $request)
    {
        if (empty($request->query->get('id_event'))) {
            return $this->redirectToRoute('user_panel_index');
        }

        $em = $this->getDoctrine()->getManager();
        $Event = $em->getRepository('EventBundle:Event')->find($request->query->get('id_event'));

        if (empty($Event) or ($Event->getUser() != $this->getUser())) {
            return $this->redirectToRoute('user_panel_index');
        }

        $documentsEvent = new DocumentsEvents();
        $form = $this->createForm('GMV\gmvEventBundle\Form\DocumentsEventsType', $documentsEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $documentsEvent->setEvent($Event);
            $em->persist($documentsEvent);
            $em->flush();
            return $this->redirectToRoute('doc_events_index', array('id' => $request->query->get('id_event')));
        }

        return $this->render('documentsevents/new.html.twig', array(
            'documentsEvent' => $documentsEvent,
            'form' => $form->createView(),
            'id_event' => $request->query->get('id_event'),
        ));
    }

    /**
     * @Route("/doc/{id}/show", name="doc_vents_show")
     * @Method("GET")
     */
    public function showDocEvents(Request $request,DocumentsEvents $documentsEvent)
    {
        if (empty($request->query->get('id_event'))) {
            return $this->redirectToRoute('user_panel_index');
        }

        $em = $this->getDoctrine()->getManager();
        $Event = $em->getRepository('EventBundle:Event')->find($request->query->get('id_event'));

        if (empty($Event) or ($Event->getUser() != $this->getUser())) {
            return $this->redirectToRoute('user_panel_index');
        }

        $deleteForm = $this->createDeleteForm($documentsEvent);

        return $this->render('documentsevents/show.html.twig', array(
            'documentsEvent' => $documentsEvent,
            'delete_form' => $deleteForm->createView(),
            'id_event' => $request->query->get('id_event'),
        ));
    }

    /**
     * Displays a form to edit an existing documentsEvent entity.
     *
     * @Route("/doc/{id}/edit", name="doc_events_edit")
     * @Method({"GET", "POST"})
     */
    public function editDocEvents(Request $request, DocumentsEvents $documentsEvent)
    {
        if (empty($request->query->get('id_event'))) {
            return $this->redirectToRoute('user_panel_index');
        }

        $em = $this->getDoctrine()->getManager();
        $Event = $em->getRepository('EventBundle:Event')->find($request->query->get('id_event'));

        if (empty($Event) or ($Event->getUser() != $this->getUser())) {
            return $this->redirectToRoute('user_panel_index');
        }

        $deleteForm = $this->createDeleteForm($documentsEvent);
        $editForm = $this->createForm('GMV\gmvEventBundle\Form\DocumentsEventsType', $documentsEvent);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('doc_events_index',  array('id' => $request->query->get('id_event')));
        }

        return $this->render('documentsevents/edit.html.twig', array(
            'documentsEvent' => $documentsEvent,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'id_event' => $request->query->get('id_event'),
        ));
    }

    /**
     * Deletes a documentsEvent entity.
     *
     * @Route("/doc/{id}", name="doc_events_delete")
     * @Method("DELETE")
     */
    public function deleteDocEvents(Request $request, DocumentsEvents $documentsEvent)
    {
        $form = $this->createDeleteForm($documentsEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($documentsEvent);
            $em->flush();
        }

        return $this->redirectToRoute('doc_events_index', array('id' => $request->query->get('id_event')));
    }

    /**
     * @param DocumentsEvents $documentsEvent The documentsEvent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(DocumentsEvents $documentsEvent)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('doc_events_delete', array('id' => $documentsEvent->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
