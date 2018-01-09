<?php

namespace GMV\gmvEventBundle\Controller;

use GMV\gmvEventBundle\Entity\Event;
use GMV\gmvEventBundle\Entity\WorkingGroup;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("userEventPanel")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class UserEventPanelController extends Controller
{
    /**
     * Lists all documentsEvent entities.
     *
     * @Route("/", name="user_panel_index")
     */
    public function showUserPanel(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $EventUser = $em->getRepository('EventBundle:Event')->createQueryFindAllUser($this->getUser()->getId());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $EventUser,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 7)
        );

        return $this->render('UserEvents/UserEventsAdmin.html.twig', array(
            'events' => $pagination
        ));

    }

    /**
     * @Route("/ParticipantsEvents", name="user_panel_participants_events_index")
     * @Method("GET")
     */
    public function showParticipantsEventsUserPanel(Request $request)
    {
        //как участник меропрития
        $em = $this->getDoctrine()->getManager();
        $EventUser = $em->getRepository('EventBundle:Event')->findAllParticipantsUser($this->getUser()->getId());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $EventUser,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 7)
        );

        return $this->render('UserEvents/UserParticipantsEventsAdmin.html.twig', array(
            'events' => $pagination
        ));
    }

    /**
     * @Route("/UserFriends", name="user_panel_User_Friends_index")
     * @Method("GET")
     */
    public function showUserFriendsPanel()
    {
        //показать моих друзей

        $em = $this->getDoctrine()->getManager();
        $FriendsUser = $em->getRepository('UserBundle:gUser')
            ->findFriendUsers($this->getUser()->getId());

        return $this->render('UserEvents/UserFreind.html.twig', array(
            'FriendsUser' => $FriendsUser,
        ));

    }
    /**
     * @Route("/UserFriendsP", name="user_panel_UserP_Friends_index")
     * @Method("GET")
     */
    public function showUserPFriendsPanel()
    {
        //показать моих друзей

        $em = $this->getDoctrine()->getManager();
        $FriendsUser = $em->getRepository('UserBundle:gUser')
            ->findFriendPUsers($this->getUser()->getId());

        return $this->render('UserEvents/UserFreind.html.twig', array(
            'FriendsUser' => $FriendsUser,
        ));

    }

    /**
     * @Route("/delete/{id}",name="user_panel_delete_event")
     * @Method("DELETE")
     * */
    public function UserPanelDeleteEvent(Event $event)
    {
        if (!$event or $event->getUser() != $this->getUser()) {
            return $this->redirectToRoute('user_panel_index');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('user_panel_index');
    }

}
