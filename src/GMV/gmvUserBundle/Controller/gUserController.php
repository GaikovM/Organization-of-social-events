<?php

namespace GMV\gmvUserBundle\Controller;

use GMV\gmvUserBundle\Entity\gUser;
use GMV\gmvUserBundle\Entity\SubscriptionToUser;
use GMV\gmvUserBundle\Form\gUserProfileType;
use GMV\gmvUserBundle\UserBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Guser controller.
 *
 * @Route("profile/user")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class gUserController extends Controller
{
    /**
     * Finds and displays a gUser entity.
     *
     * @Route("/{id}", name="guser_show")
     * @Method({"GET", "POST"})
     */
    public function showUser(Request $request, gUser $gUser)
    {

        $em = $this->getDoctrine()->getManager();
        $IsFreind = $em->getRepository('UserBundle:SubscriptionToUser')->findUserFreind($this->getUser(), $gUser);
        $you = $this->getUser()->getId() == $gUser->getId();

        $form = $this->createForm(gUserProfileType::class, $gUser, ['postedBy' => empty($IsFreind), 'you' => $you]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $SubToUser = new SubscriptionToUser();

            if (empty($IsFreind) and $form->get('addFriend')->isClicked()) {

                $SubToUser->setUser($user);
                $SubToUser->setUserFriend($gUser);
                $em->persist($SubToUser);
                $em->flush();
                $form = $this->createForm(gUserProfileType::class, $gUser, ['postedBy' => false]);

            } elseif ($form->get('deleteFriend')->isClicked()) {

                //$SubToUsers = $em->getRepository('UserBundle:SubscriptionToUser')->findUserFreind($this->getUser(), $gUser);
                foreach ($IsFreind as $SubToUser) {
                    $em->remove($SubToUser);
                }
                $em->flush();
                $form = $this->createForm(gUserProfileType::class, $gUser, ['postedBy' => true]);
            }
        };

        return $this->render('guser/show.html.twig', array(
            'gUser' => $gUser,
            'form' => $form->createView(),
        ));
    }


    /**
     * @param gUser $gUser The gUser entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(gUser $gUser)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('guser_delete', array('id' => $gUser->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
