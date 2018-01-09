<?php

namespace GMV\gmvUserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('about',TextareaType::class, array(
                'required' => false, 'label' => 'О себе:'
            ))
            ->add('city', TextType::class, array(
                'required' => false, 'label' => 'Город:'
            ))
            ->add('gender',EntityType ::class, array(
                'class' => 'GMV\gmvEventBundle\Entity\Addition\Gender',
                'label' => 'Пол:'))
            ->add('dateofbirth',DateType::class, array(
                'widget' => 'single_text','html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'label' => 'Дата рождения: '))
            ->add('occupation',TextareaType::class, array(
                'required' => false, 'label' => 'Интересы:'
            ))
            ->add('site',UrlType::class, array(
                'required' => false, 'label' => 'Сайт:'
            ))
            ->add('image',UserImageType::class, [
                 'required' => false,
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GMV\gmvUserBundle\Entity\gUser'
        ));
    }

//    public function getParent()
//    {
//        return 'FOS\UserBundle\Form\Type\ProfileFormType';
//
//    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }


}
