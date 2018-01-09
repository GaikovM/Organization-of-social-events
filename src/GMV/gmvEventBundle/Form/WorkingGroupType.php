<?php

namespace GMV\gmvEventBundle\Form;

use GMV\gmvUserBundle\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class WorkingGroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                'label' => 'Участник',
                'class' => 'UserBundle:gUser',
                'query_builder' => function (UserRepository $er) use ($options) {
                    if ($options['key']=='p') {
                        return $er->findFriendUsers_Form($options['id_user']);
                    } elseif ($options['key']=='g') {
                        return $er->findEventUsers_Form($options['event']);
                    }
                },
            ])
            ->add('categoryUsersEvent',EntityType::class, [
                'label' => 'Категория участника',
                'class' => 'EventBundle:CategoryUsersEvent']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GMV\gmvEventBundle\Entity\WorkingGroup',
            'key' => null,
            'event' => null,
            'id_user'=> null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gmv_gmveventbundle_workinggroup';
    }


}
