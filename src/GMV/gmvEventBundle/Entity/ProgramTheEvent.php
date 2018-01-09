<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 30.10.2017
 * Time: 2:59
 */

namespace GMV\gmvEventBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="program_the_event")
 */
class ProgramTheEvent
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
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="event", inversedBy="programTheEvent")
     * @ORM\JoinColumn(name="event_id",referencedColumnName="id")
     */
    private $event;


    /**
     * @Assert\Time()
     * @ORM\Column(type="time",nullable=false)
     * @Assert\Expression(
     *     "this.getTimeFinish() > this.getTimeStart()",
     *     message="Время начала должна быть меньше времени окончания!"
     * )
     */
    private $time_start;


    /**
     * @Assert\Time()
     * @ORM\Column(type="time",nullable=false)
     * @Assert\Expression(
     *     "this.getTimeFinish() > this.getTimeStart()",
     *     message="Время окончания должна быть больше времени начала!"
     * )
     */
    private $time_finish;


    /**
     * @ORM\ManyToOne(targetEntity="\GMV\gmvUserBundle\Entity\gUser", inversedBy="programtheevent")
     * @ORM\Column(type="responsible",nullable=true)
     */
    private $responsible;

    /**
     * @ORM\Column(name="responsible_text", type="string", length=255, nullable=true)
     */
    private $responsible_text;

      

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
     * Set content
     *
     * @param string $content
     *
     * @return ProgramTheEvent
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set timeStart
     *
     * @param \DateTime $timeStart
     *
     * @return ProgramTheEvent
     */
    public function setTimeStart($timeStart)
    {
        $this->time_start = $timeStart;

        return $this;
    }

    /**
     * Get timeStart
     *
     * @return \DateTime
     */
    public function getTimeStart()
    {
        return $this->time_start;
    }

    /**
     * Set timeFinish
     *
     * @param \DateTime $timeFinish
     *
     * @return ProgramTheEvent
     */
    public function setTimeFinish($timeFinish)
    {
        $this->time_finish = $timeFinish;

        return $this;
    }

    /**
     * Get timeFinish
     *
     * @return \DateTime
     */
    public function getTimeFinish()
    {
        return $this->time_finish;
    }

    /**
     * Set event
     *
     * @param \GMV\gmvEventBundle\Entity\event $event
     *
     * @return ProgramTheEvent
     */
    public function setEvent(\GMV\gmvEventBundle\Entity\event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \GMV\gmvEventBundle\Entity\event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set responsible
     *
     * @param \GMV\gmvUserBundle\Entity\gUser $responsible
     *
     * @return ProgramTheEvent
     */
    public function setResponsible(\GMV\gmvUserBundle\Entity\gUser $responsible = null)
    {
        $this->responsible = $responsible;

        return $this;
    }

    /**
     * Get responsible
     *
     * @return \GMV\gmvUserBundle\Entity\gUser
     */
    public function getResponsible()
    {
        return $this->responsible;
    }

    /**
     * Set responsibleText
     *
     * @param string $responsibleText
     *
     * @return ProgramTheEvent
     */
    public function setResponsibleText($responsibleText)
    {
        $this->responsible_text = $responsibleText;

        return $this;
    }

    /**
     * Get responsibleText
     *
     * @return string
     */
    public function getResponsibleText()
    {
        return $this->responsible_text;
    }
}
