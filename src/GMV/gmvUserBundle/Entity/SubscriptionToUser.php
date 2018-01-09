<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 03.11.2017
 * Time: 18:39
 */

namespace GMV\gmvUserBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="subscription_to_user")
 * @ORM\Entity(repositoryClass="GMV\gmvUserBundle\Repository\SubscriptionToUserRepository")
 */
class SubscriptionToUser
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;


    /**
     * @ORM\ManyToOne(targetEntity="gUser", inversedBy="userSubUser")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity="gUser", inversedBy="userSubFreind")
     * @ORM\JoinColumn(name="UserFriend_id",referencedColumnName="id")
     */
    private $userFriend;


    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getUsername();
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }




    /**
     * Set user
     *
     * @param \GMV\gmvUserBundle\Entity\gUser $user
     *
     * @return SubscriptionToUser
     */
    public function setUser(\GMV\gmvUserBundle\Entity\gUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \GMV\gmvUserBundle\Entity\gUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set userFriend
     *
     * @param \GMV\gmvUserBundle\Entity\gUser $userFriend
     *
     * @return SubscriptionToUser
     */
    public function setUserFriend(\GMV\gmvUserBundle\Entity\gUser $userFriend = null)
    {
        $this->userFriend = $userFriend;

        return $this;
    }

    /**
     * Get userFriend
     *
     * @return \GMV\gmvUserBundle\Entity\gUser
     */
    public function getUserFriend()
    {
        return $this->userFriend;
    }
}
