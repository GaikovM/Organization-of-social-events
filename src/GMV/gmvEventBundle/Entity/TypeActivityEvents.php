<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 27.10.2017
 * Time: 18:56
 */

namespace GMV\gmvEventBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="type_оf_activity_events")
 */
class TypeОfActivityEvents
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
     * @ORM\OneToOne(targetEntity="Event", mappedBy="$typeОfActivityEvent")
     */
    private $event;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}