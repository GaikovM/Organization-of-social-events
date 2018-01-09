<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 27.10.2017
 * Time: 19:17
 */

namespace GMV\gmvEventBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="the_target_audience")
 */
class TheTargetAudience
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;


    // ...
    /**
     * @ORM\OneToOne(targetEntity="Event", mappedBy="$thetargetaudience")
     */
    private $event;


    /**
     *
     * @ORM\Column(name="access", type="boolean")
     */
    private $Access;

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
     * Set name
     *
     * @param string $name
     *
     * @return TheTargetAudience
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set access
     *
     * @param boolean $access
     *
     * @return TheTargetAudience
     */
    public function setAccess($access)
    {
        $this->Access = $access;

        return $this;
    }

    /**
     * Get access
     *
     * @return boolean
     */
    public function getAccess()
    {
        return $this->Access;
    }

    /**
     * Set event
     *
     * @param \GMV\gmvEventBundle\Entity\Event $event
     *
     * @return TheTargetAudience
     */
    public function setEvent(\GMV\gmvEventBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \GMV\gmvEventBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}
