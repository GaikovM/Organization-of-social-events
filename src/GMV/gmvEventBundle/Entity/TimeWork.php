<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 06.12.2017
 * Time: 18:34
 */

namespace GMV\gmvEventBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="time_work")
 */
class TimeWork
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;



//    /**
//    * @ORM\ManyToOne(targetEntity="event", inversedBy="programTheEvent")
//    * @ORM\JoinColumn(name="event_id",referencedColumnName="id")
//    */
//    private $work_id;


    /**
     * @var \DateTime
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $datetime_start;


    /**
     * @var \DateTime
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $datetime_finish;


    /**
     * @ORM\ManyToOne(targetEntity="\GMV\gmvEventBundle\Entity\Addition\Status")
     * @ORM\JoinColumn(name="status_id",referencedColumnName="id")
     */
    private $status;

}