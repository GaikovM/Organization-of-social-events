<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 30.10.2017
 * Time: 3:13
 */

namespace GMV\gmvEventBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="working_group_protocol")
 * @ORM\Entity(repositoryClass="GMV\gmvEventBundle\Repository\WorkingGroupProtocolRepository")
 *
 */
class WorkingGroupProtocol
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type = "text")
     * @Assert\NotBlank(message="Заполните описание протокола")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="WorkingGroupNameProtocol")
     * @ORM\JoinColumn(name="WorkingGroupNameProtocol_id",referencedColumnName="id")
     */
    private $workingGroupNameProtocol;


    /**
      * @ORM\ManyToOne(targetEntity="\GMV\gmvUserBundle\Entity\gUser")
     * @ORM\JoinColumn(name="User_id",referencedColumnName="id")
     */
    private $user;



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
     * Set workingGroupNameProtocol
     *
     * @param \GMV\gmvEventBundle\Entity\WorkingGroupNameProtocol $workingGroupNameProtocol
     *
     * @return WorkingGroupProtocol
     */
    public function setWorkingGroupNameProtocol(\GMV\gmvEventBundle\Entity\WorkingGroupNameProtocol $workingGroupNameProtocol = null)
    {
        $this->workingGroupNameProtocol = $workingGroupNameProtocol;

        return $this;
    }

    /**
     * Get workingGroupNameProtocol
     *
     * @return \GMV\gmvEventBundle\Entity\WorkingGroupNameProtocol
     */
    public function getWorkingGroupNameProtocol()
    {
        return $this->workingGroupNameProtocol;
    }

    /**
     * Set user
     *
     * @param \GMV\gmvUserBundle\Entity\gUser $user
     *
     * @return WorkingGroupProtocol
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
     * Set name
     *
     * @param string $name
     *
     * @return WorkingGroupProtocol
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    function __toString()
    {
        return $this->getName();
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
}
