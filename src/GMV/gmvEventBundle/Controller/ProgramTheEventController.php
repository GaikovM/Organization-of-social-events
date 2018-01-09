<?php

namespace GMV\gmvEventBundle\Controller;

use GMV\gmvEventBundle\Entity\ProgramTheEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Programtheevent controller.
 *
 * @Route("program")
 */
class ProgramTheEventController extends Controller
{
    /**
     * Lists all programTheEvent entities.
     *
     * @Route("/", name="program_index")
     * @Method("GET")
     */
    public function indexProgramEvent()
    {
        $em = $this->getDoctrine()->getManager();

        $programTheEvents = $em->getRepository('EventBundle:ProgramTheEvent')->findAll();

        return $this->render('programtheevent/index.html.twig', array(
            'programTheEvents' => $programTheEvents,
        ));
    }

    /**
     * Creates a new programTheEvent entity.
     *
     * @Route("/new", name="program_new")
     * @Method({"GET", "POST"})
     */
    public function newProgramEvent(Request $request)
    {
        $programTheEvent = new Programtheevent();
        $form = $this->createForm('GMV\gmvEventBundle\Form\ProgramTheEventType', $programTheEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($programTheEvent);
            $em->flush();

            return $this->redirectToRoute('program_show', array('id' => $programTheEvent->getId()));
        }

        return $this->render('programtheevent/new.html.twig', array(
            'programTheEvent' => $programTheEvent,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a programTheEvent entity.
     *
     * @Route("/{id}", name="program_show")
     * @Method("GET")
     */
    public function showProgramEvent(ProgramTheEvent $programTheEvent)
    {
        $deleteForm = $this->createDeleteForm($programTheEvent);

        return $this->render('programtheevent/show.html.twig', array(
            'programTheEvent' => $programTheEvent,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing programTheEvent entity.
     *
     * @Route("/{id}/edit", name="program_edit")
     * @Method({"GET", "POST"})
     */
    public function editProgramEvent(Request $request, ProgramTheEvent $programTheEvent)
    {
        $deleteForm = $this->createDeleteForm($programTheEvent);
        $editForm = $this->createForm('GMV\gmvEventBundle\Form\ProgramTheEventType', $programTheEvent);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('program_edit', array('id' => $programTheEvent->getId()));
        }

        return $this->render('programtheevent/edit.html.twig', array(
            'programTheEvent' => $programTheEvent,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a programTheEvent entity.
     *
     * @Route("/{id}", name="program_delete")
     * @Method("DELETE")
     */
    public function deleteProgramEvent(Request $request, ProgramTheEvent $programTheEvent)
    {
        $form = $this->createDeleteForm($programTheEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($programTheEvent);
            $em->flush();
        }

        return $this->redirectToRoute('program_index');
    }

    /**
     * Creates a form to delete a programTheEvent entity.
     *
     * @param ProgramTheEvent $programTheEvent The programTheEvent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ProgramTheEvent $programTheEvent)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('program_delete', array('id' => $programTheEvent->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
