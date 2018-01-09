<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 28.10.2017
 * Time: 2:41
 */

namespace GMV\gmvEventBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="documents_events")
 * @Vich\Uploadable
 */
class DocumentsEvents
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="documentsEvents")
     */
    private $event;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     *
     * @var string
     */
    protected $image = null;

    /**
     * @Assert\File(maxSize="15M")
     * @Vich\UploadableField(mapping="assets", fileNameProperty="image")
     *
     * @var File
     */
    protected $imageFile;

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (! in_array($this->imageFile->getMimeType(), array(
            'application/msword',
            'application/vnd.ms-excel',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.presentationml.presentatio',
            'application/vnd.oasis.opendocument.text',
            'application/pdf',
            'application/ogg',
            'application/x-rar-compressed',
            'application/zip',
            'application/gzip',
            'text/csv',
            'image/jpeg',
            'image/gif',
            'image/png',

        ))) {
            $context
                ->buildViolation('Формат файла должен соответсовать (jpg,gif,png,pdf,ogg,csv,doc,docx,xls,ppt,pptx )')
                ->atPath('imageFile')
                ->addViolation()
            ;
        }
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
     * Set name
     *
     * @param string $name
     * @return DocumentsEvents
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
     * @param string $image
     *
     * @return Category
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return DocumentsEvents
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        return $this;
    }

    /**
     * Returns the image to be uploaded.
     *
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set event
     *
     * @param \GMV\gmvEventBundle\Entity\Event $event
     *
     * @return DocumentsEvents
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
