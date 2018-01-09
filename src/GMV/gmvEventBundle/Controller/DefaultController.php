<?php

namespace GMV\gmvEventBundle\Controller;

use GMV\gmvEventBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/event/{eventName}")
     */
    public function showEvent($eventName)
    {
        return $this->render('EventBundle::show.html.twig', array(
            'name' => $eventName,
        ));
    }

    /**
     * @Route("/event/new/{eventName}")
     */
    public function newEvent($eventName)
    {
        $event = new Event();
        $event->setName($eventName);

        $em = $this->getDoctrine()->getManager();
        $em->persist($event);
        $em->flush();

        return new Response('Событие созадно');
    }


}
