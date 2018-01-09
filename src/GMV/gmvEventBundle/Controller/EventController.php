<?php

namespace GMV\gmvEventBundle\Controller;

use GMV\gmvEventBundle\Entity\Event;
use GMV\gmvEventBundle\Entity\ParticipantsEvents;
use GMV\gmvEventBundle\Form\EventViewShowType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use GeoJson\JsonUnserializable;
use GeoJson\GeoJson;
use GeoJson\Feature\FeatureCollection;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use GuzzleHttp\Client;

/**
 * Event controller.
 *
 * @Route("event")
 */
class EventController extends Controller
{
    /**
     * Lists all event entities.
     *
     * @Route("/", name="event_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('EventBundle:Event')->findAll();

        return $this->render('event/index.html.twig', array(
            'events' => $events,
        ));
    }


    /**
     * @Route("/api/programmers")
     * @Method({"GET", "POST"})
     *
     */
    public function showAction_()
    {
        return new Response('Let\'s do this!');
    }


    /**
     * @Route("/api/p")
     */
    public function showActionDD()
    {
        $client = $this->get('eight_points_guzzle.client.api_crm');
        $response = $client->request('GET', 'profile/user/1');
        var_dump($response);
        return new Response($response->getBody());
    }

    /**
     * Creates a new event entity.
     *
     * @Route("/new", name="event_new")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method({"GET", "POST"})
     */
    public function newEvent(Request $request)
    {
        $event = new Event();
        $form = $this->createForm('GMV\gmvEventBundle\Form\EventType', $event,['event_home_folder' =>$this->folderUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $event->setUser($this->getUser());
            $json = json_decode($event->getCoordinates());
            $json = \GeoJson\GeoJson::jsonUnserialize($json);
            $event->setCoordinates($json);
            $em->persist($event);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Поздавляем ! Вы создали мероприятие');

            return $this->redirectToRoute('user_panel_index', array('id' => $event->getId()));
        }
        return $this->render('event/new.html.twig', array(
            'event' => $event,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a event entity.
     *
     * @Route("/{id}", name="event_show")
     * @Method({"GET", "POST"})
     */
    public function showEvent(Request $request, Event $event)
    {
        $em = $this->getDoctrine()->getManager();
        $ActiveUserEvents = $em->getRepository('UserBundle:gUser')->findAllUsersEvents($event->getId());


        $docEvents = $em->getRepository('EventBundle:DocumentsEvents')->findAllDocEvent($event->getId());

        $arg = array(
            'documentsEvents' => $docEvents,
            'event' => $event,
        );
        $ActiveUser = array('ActiveUser' => $ActiveUserEvents);
        $arg = $arg + $ActiveUser;

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $ActiveOneUser = $em->getRepository('UserBundle:gUser')->findOneUserEvent($event->getId(), $this->getUser()->getId());
            $form = $this->createForm(EventViewShowType::class, $event, ['ImAMember' => empty($ActiveOneUser)]);
        } else {
            $ActiveOneUser = false;
            $form = $this->createForm(EventViewShowType::class, $event, ['ImAMember' => null]);
        };
        $CreateForm = array('form' => $form->createView());
        $arg = $arg + $CreateForm;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (empty($ActiveOneUser) and $form->get('BecomeEventUser')->isClicked()) {
                $PE = new ParticipantsEvents();
                $PE->setUser($this->getUser());
                $PE->setEvent($event);
                $em->persist($PE);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Поздавляем ! Вы стали участником мероприятия');
                $formNew = $this->createForm(EventViewShowType::class, $event, ['ImAMember' => false]);
                $arg = array_replace($arg, $CreateForm, array('form' => $formNew->createView()));
                $ActiveUserEventsNew = $em->getRepository('UserBundle:gUser')->findAllUsersEvents($event->getId());
                $arg = array_replace($arg, $ActiveUser, array('ActiveUser' => $ActiveUserEventsNew));

            } elseif ($form->get('LeaveEventUser')->isClicked()) {
                $em = $this->getDoctrine()->getManager();
                $UserEvents = $em->getRepository('EventBundle:ParticipantsEvents')->findUserEvent($this->getUser()->getId(), $event->getId());
                foreach ($UserEvents as &$UserEvent) {
                    $em->remove($UserEvent);
                }
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Мероприятие удалено из ваших мероприятий');
                $formNew = $this->createForm(EventViewShowType::class, $event, ['ImAMember' => true]);
                $arg = array_replace($arg, $CreateForm, array('form' => $formNew->createView()));
                $ActiveUserEventsNew = $em->getRepository('UserBundle:gUser')->findAllUsersEvents($event->getId());
                $arg = array_replace($arg, $ActiveUser, array('ActiveUser' => $ActiveUserEventsNew));

            }
        }

        return $this->render('event/show.html.twig', $arg);
    }

    public function folderUser()
    {

        $project = $this->get('kernel')->getProjectDir() . '\web\uploads/';
        $fs = new Filesystem();

        $value = $fs->exists($project.'/user_'.$this->getUser()->getusername());

        if ($value == false) {
            try {
                $fs->mkdir($project .'/user_'. $this->getUser()->getusername());
            } catch (IOExceptionInterface $e) {
            }
        }

        return 'user_'.$this->getUser()->getusername();
    }

    /**
     * Displays a form to edit an existing event entity.
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/{id}/edit", name="event_edit")
     * @Method({"GET", "POST"})
     */
    public function editEvent(Request $request, Event $event)
    {

        if ($this->getUser() <> $event->getUser()) {
            $this->get('session')->getFlashBag()->add('error', 'Доступ запрещен!');
            return $this->redirect($this->generateUrl(
                'event_show',
                array('id' => $event->getId())));
        }


        $event->setCoordinates(json_encode($event->getCoordinates()));
        $deleteForm = $this->createDeleteForm($event);
        $editForm = $this->createForm('GMV\gmvEventBundle\Form\EventType', $event, ['event_home_folder' =>$this->folderUser()]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $json = json_decode($event->getCoordinates());
            $json = \GeoJson\GeoJson::jsonUnserialize($json);
            $event->setCoordinates($json);

            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', 'Мероприятие изменено');
            return $this->redirectToRoute('user_panel_index');
        }

        return $this->render('event/edit.html.twig', array(
            'event' => $event,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a event entity.
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/{id}", name="event_delete")
     * @Method("DELETE")
     */
    public function deleteEvent(Request $request, Event $event)
    {
        $form = $this->createDeleteForm($event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Мероприятие удалено');
        }

        return $this->redirectToRoute('event_index');
    }

    /**
     * @param Event $event The event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Event $event)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', array('id' => $event->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
