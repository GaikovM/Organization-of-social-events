<?php

namespace GMV\gmvUserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class gUserProfileType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($options['you'] == false) {
            if ($options['postedBy'] == false) {
                $builder
                    ->add('deleteFriend', SubmitType::class, array('label' => 'Удалить из подписки'));
            } else {
                $builder
                    ->add('addFriend', SubmitType::class, array('label' => 'Добавить в подписки'));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GMV\gmvUserBundle\Entity\gUser',
            'postedBy' => null,
            'you' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gmv_gmvuserbundle_guser';
    }


}
