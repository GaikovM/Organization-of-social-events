<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 02.11.2017
 * Time: 22:41
 */

namespace GMV\gmvUserBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class UserImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', TextType::class, [
                'label' => false,'required' => 'true','disabled'=> 'true',
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => false, 'allow_delete' => false,
                'required' => false,'download_label' => 'Загрузить файл',
                'imagine_pattern' => 'user_photo_140x140',
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GMV\gmvUserBundle\Entity\ImageUser'
        ));
    }
}
