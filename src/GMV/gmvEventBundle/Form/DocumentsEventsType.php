<?php

namespace GMV\gmvEventBundle\Form;

use GMV\gmvEventBundle\Entity\DocumentsEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\PropertyAccess\PropertyPath;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\{
    SubmitType,
    TextType,TextareaType
};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentsEventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextareaType::class, [
                'label' => 'Описание '
            ])
            ->add('image', TextType::class, [
                'label' => false,'required' => 'true','disabled'=> 'true',
            ])
            ->add('imageFile', VichFileType::class, [
                'label' => false, 'allow_delete' => false,
                'required' => false,'download_label' => 'Загрузить файл',
            ])

        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GMV\gmvEventBundle\Entity\DocumentsEvents'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gmv_gmveventbundle_documentsevents';
    }


}
