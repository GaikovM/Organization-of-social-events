<?php

namespace GMV\gmvEventBundle\Controller;

use GMV\gmvEventBundle\Entity\WorkingGroupProtocol;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("event")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class WorkingGroupProtocolController extends Controller
{
    /**
     * @Route("/tasks/{id}", name="task_work_index")
     */
    public function indexWorkingGroupProtocol($id,Request $request)
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
        //$tasks = $em->getRepository('EventBundle:WorkingGroupProtocol')->GetEventTask($id);

        $tasksQ = $em->getRepository('EventBundle:WorkingGroupProtocol')->GetEventTaskQ($id);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $tasksQ,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 7)
        );

        return $this->render('tasks/index.html.twig', array(
            'tasks' => $pagination,
            'id_event' => $id,
            'event_name' => $Event->getName(),

        ));
    }


    /**
     * @Route("/user/tasks/", name="user_task_work_index")
     * @Method("GET")
     */
    public function indexUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('EventBundle:WorkingGroupProtocol')->GetEventTaskUser($this->getUser()->getId());

        return $this->render('UserEvents/UserMyTask.html.twig', array(
            'tasks' => $tasks,
        ));
    }


    /**
     * @Route("/user/create/tasks/", name="user_creater_task_work_index")
     * @Method("GET")
     */
    public function indexUserCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //$tasks = $em->getRepository('EventBundle:WorkingGroupProtocol')->GetAllTaskUser($this->getUser()->getId());

        $tasksQ = $em->getRepository('EventBundle:WorkingGroupProtocol')->GetAllTaskUserQ($this->getUser()->getId());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $tasksQ,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );


        return $this->render('UserEvents/UserMyCreatedTasks.html.twig', array(
            'tasks' => $pagination,
        ));
    }


    /**
     * Creates a new workingGroupProtocol entity.
     *
     * @Route("/tasks/new/", name="task_work_new")
     * @Method({"GET", "POST"})
     */
    public function newWorkingGroupProtocol(Request $request)
    {

        if (empty($request->query->get('id_event'))) {
            return $this->redirectToRoute('user_panel_index');
        }

        $em = $this->getDoctrine()->getManager();
        $Event = $em->getRepository('EventBundle:Event')->find($request->query->get('id_event'));

        if (empty($Event) or ($Event->getUser() != $this->getUser())) {
            return $this->redirectToRoute('user_panel_index');
        }

        $workingGroupProtocol = new Workinggroupprotocol();
        $form = $this->createForm('GMV\gmvEventBundle\Form\WorkingGroupProtocolType', $workingGroupProtocol, ['event' => $request->query->get('id_event')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $workingGroupProtocol->setEvent($Event);
            $workingGroupProtocol->setCreatorUser($this->getUser());
            $em->persist($workingGroupProtocol);
            $em->flush();
            return $this->redirectToRoute('task_work_index', array('id' => $request->query->get('id_event')));
        }

        return $this->render('tasks/new.html.twig', array(
            'workingGroupProtocol' => $workingGroupProtocol,
            'form' => $form->createView(),
            'id_event' => $request->query->get('id_event'),
        ));
    }

    /**
     * @Route("/tasks/{id}/show", name="task_work_show")
     * @Method("GET")
     */
    public function showWorkingGroupProtocol(Request $request, WorkingGroupProtocol $workingGroupProtocol)
    {
        if (empty($request->query->get('id_event'))) {
            return $this->redirectToRoute('user_panel_index');
        }

        $em = $this->getDoctrine()->getManager();
        $Event = $em->getRepository('EventBundle:Event')->find($request->query->get('id_event'));

        if (empty($Event) or ($Event->getUser() != $this->getUser())) {
            return $this->redirectToRoute('user_panel_index');
        }

        $deleteForm = $this->createDeleteForm($workingGroupProtocol);

        return $this->render('tasks/show.html.twig', array(
            'task' => $workingGroupProtocol,
            'delete_form' => $deleteForm->createView(),
            'id_event' => $request->query->get('id_event'),

        ));
    }

    /**
     * @Route("/mytasks/tasks/{id}/show", name="user_task_work_show")
     * @Method("GET")
     */
    public function UserTaskShowAction(Request $request, WorkingGroupProtocol $workingGroupProtocol)
    {
        if (empty($request->query->get('id_event'))) {
            return $this->redirectToRoute('user_task_work_index');
        }

        $em = $this->getDoctrine()->getManager();
        $Event = $em->getRepository('EventBundle:Event')->find($request->query->get('id_event'));

        if ($workingGroupProtocol->getUser()!= $this->getUser()) {
            return $this->redirectToRoute('user_task_work_index');
        }

        return $this->render('tasks/UserTasks/show.html.twig', array(
            'task' => $workingGroupProtocol,
            'id_event' => $request->query->get('id_event'),
        ));
    }

    /**
     * Displays a form to edit an existing workingGroupProtocol entity.
     *
     * @Route("/tasks/{id}/edit", name="task_work_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, WorkingGroupProtocol $workingGroupProtocol)
    {

        if (empty($request->query->get('id_event'))) {
            return $this->redirectToRoute('user_panel_index');
        }

        $em = $this->getDoctrine()->getManager();
        $Event = $em->getRepository('EventBundle:Event')->find($request->query->get('id_event'));

        if (empty($Event) or ($Event->getUser() != $this->getUser())) {
            return $this->redirectToRoute('user_panel_index');
        }

        $deleteForm = $this->createDeleteForm($workingGroupProtocol);

        $editForm = $this->createForm('GMV\gmvEventBundle\Form\WorkingGroupProtocolType', $workingGroupProtocol, ['event' => $request->query->get('id_event')]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('task_work_index', array('id' => $request->query->get('id_event')));
        }

        return $this->render('tasks/edit.html.twig', array(
            'workingGroupProtocol' => $workingGroupProtocol,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'id_event' => $request->query->get('id_event'),
        ));
    }


    /**
     * Displays a form to edit an existing workingGroupProtocol entity.
     *
     * @Route("/mytasks/tasks/{id}/edit", name="user_task_work_edit")
     * @Method({"GET", "POST"})
     */
    public function editUserTaskAction(Request $request, WorkingGroupProtocol $workingGroupProtocol)
    {

        if (empty($request->query->get('id_event'))) {
            return $this->redirectToRoute('user_task_work_index');
        }

        if ($workingGroupProtocol->getUser() != $this->getUser()) {
            return $this->redirectToRoute('user_task_work_index');
        }


        $editForm = $this->createForm('GMV\gmvEventBundle\Form\UserTaskProtocolType', $workingGroupProtocol);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_task_work_index');
        }

        return $this->render('tasks/UserTasks/edit.html.twig', array(
            'task' => $workingGroupProtocol,
            'edit_form' => $editForm->createView(),
            'id_event' => $request->query->get('id_event'),
        ));
    }
    /**
     * Deletes a workingGroupProtocol entity.
     *
     * @Route("/tasks/{id}", name="task_work_delete")
     * @Method("DELETE")
     */
    public function deleteWorkingGroupProtocol(Request $request, WorkingGroupProtocol $workingGroupProtocol)
    {
        $form = $this->createDeleteForm($workingGroupProtocol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($workingGroupProtocol);
            $em->flush();
        }
        return $this->redirectToRoute('task_work_index', array('id' => $request->query->get('id_event')));
    }

    /**
     * Creates a form to delete a workingGroupProtocol entity.
     *
     * @param WorkingGroupProtocol $workingGroupProtocol The workingGroupProtocol entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(WorkingGroupProtocol $workingGroupProtocol)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_work_delete', array('id' => $workingGroupProtocol->getId(),
                'id_event' => $workingGroupProtocol->getEvent()->getId(),
            )))
            ->setMethod('DELETE')
            ->getForm();
    }
}
