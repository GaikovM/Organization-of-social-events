<?php

namespace GMV\gmvEventBundle\Controller;

use GMV\gmvEventBundle\Entity\WorkingGroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("event")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class WorkingGroupController extends Controller
{
    /**
     * @Route("/work/{id}", name="event_workinggroup_index")
     * @Method("GET")
     */
    public function indexActionWorkGroup($id)
    {

        if (empty($id)) {
            $this->get('session')->getFlashBag()->add('warning', 'Что-то пошло не так');
            return $this->redirectToRoute('user_panel_index');
        }
        $em = $this->getDoctrine()->getManager();
        $Event = $em->getRepository('EventBundle:Event')->find($id);


        if (empty($Event) or ($Event->getUser() != $this->getUser())) {
            $this->get('session')->getFlashBag()->add('error', 'Доступ запрещен');
            return $this->redirectToRoute('user_panel_index');
        }

        $em = $this->getDoctrine()->getManager();
        $workingGroup = $em->getRepository('EventBundle:WorkingGroup')->FindEventGroup($id);
        return $this->render('workinggroup/index.html.twig', array(
            'workingGroups' => $workingGroup,
            'id_event' => $id,
            'event_name' => $Event->getName(),
        ));
    }

    /**
     *
     * @Route("/work/new/", name="event_workinggroup_new")
     * @Method({"GET", "POST"})
     */
    public function newWorkGroup(Request $request)
    {

        if ((empty($request->query->get('id_event'))) or (empty($request->query->get('key')))) {
            $this->get('session')->getFlashBag()->add('warning', 'Упс, что-то пошло не так...');
            return $this->redirectToRoute('user_panel_index');
        }
        if (($request->query->get('key') != "g") && ($request->query->get('key') != 'p')) {
            $this->get('session')->getFlashBag()->add('warning', 'Упс, что-то пошло не так...');
            return $this->redirectToRoute('user_panel_index');
        }

        $em = $this->getDoctrine()->getManager();
        $Event = $em->getRepository('EventBundle:Event')->find($request->query->get('id_event'));


        if (empty($Event) or ($Event->getUser() != $this->getUser())) {
            $this->get('session')->getFlashBag()->add('error', 'Доступ запрещен');
            return $this->redirectToRoute('user_panel_index');
        }

        $workingGroup = new Workinggroup();
        $form = $this->createForm('GMV\gmvEventBundle\Form\WorkingGroupType', $workingGroup, ['key' => $request->query->get('key'), 'event' => $request->query->get('id_event'), 'id_user' => $this->getUser()->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $Event = $em->getRepository('EventBundle:Event')->find($request->query->get('id_event'));
            $workingGroup->setEvent($Event);
            $em->persist($workingGroup);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Добавлен участник в рабочую группу');
            return $this->redirectToRoute('event_workinggroup_index', array('id' => $request->query->get('id_event')));
        }

        return $this->render('workinggroup/new.html.twig', array(
            'id_event' => $request->query->get('id_event'),
            'workingGroup' => $workingGroup,
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/work/edit/{id}", name="event_workinggroup_edit")
     * @Method({"GET", "POST"})
     */
    public function editWorkGroup(Request $request, WorkingGroup $workingGroup)
    {
        if ((empty($request->query->get('id_event'))) or (empty($request->query->get('key')))) {
            $this->get('session')->getFlashBag()->add('warning', 'Упс, что-то пошло не так...');
            return $this->redirectToRoute('user_panel_index');
        }
        if (($request->query->get('key') != "g") && ($request->query->get('key') != 'p')) {
            $this->get('session')->getFlashBag()->add('warning', 'Упс, что-то пошло не так...');
            return $this->redirectToRoute('user_panel_index');
        }

        $em = $this->getDoctrine()->getManager();
        $Event = $em->getRepository('EventBundle:Event')->find($request->query->get('id_event'));


        if (empty($Event) or ($Event->getUser() != $this->getUser())) {
            $this->get('session')->getFlashBag()->add('error', 'Доступ запрещен');
            return $this->redirectToRoute('user_panel_index');
        }

        $editForm = $this->createForm('GMV\gmvEventBundle\Form\WorkingGroupType', $workingGroup, ['key' => $request->query->get('key'), 'event' => $request->query->get('id_event'), 'id_user' => $this->getUser()->getId()]);
        $deleteForm = $this->createDeleteFormWorkGroup($workingGroup);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', 'Значение изменено');
            return $this->redirectToRoute('user_panel_index');
        }

        return $this->render('workinggroup/edit.html.twig', array(
            'workingGroup' => $workingGroup,
            'id_event' => $request->query->get('id_event'),
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/work/{id}", name="event_workinggroup_delete")
     * @Method("DELETE")
     */
    public function WorkdeleteAction(Request $request, WorkingGroup $workingGroup)
    {
        $form = $this->createDeleteFormWorkGroup($workingGroup);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($workingGroup);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Участник удален из рабочей группы');
        }

        return $this->redirectToRoute('user_panel_index');
    }

    /**
     * @param WorkingGroup $workingGroup The workingGroup entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteFormWorkGroup(WorkingGroup $workingGroup)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_workinggroup_delete', array('id' => $workingGroup->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }


}
