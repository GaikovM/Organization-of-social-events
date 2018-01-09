<?php

namespace GMV\gmvEventBundle\Form;

use GMV\gmvUserBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkingGroupProtocolType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextareaType::class, array(
            'label' => 'Описания задания'
        ))


            ->add('date_finish', DateType::class, array(
                'widget' => 'single_text','html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'label' => 'Срок выполнения'))


            ->add('status', EntityType::class, [
                    'label' => 'Статус',
                    'class' => 'EventBundle:Addition\Status']
                )

            ->add('place', TextType::class, array(
                'label' => 'Описания места'
            ))
            ->add('responsible_text', TextType::class, array(
                'label' => 'Дополнительные участники текстом'
            ))

            ->add('user', EntityType::class, [
                'label' => 'Пользователь',
                'class' => 'UserBundle:gUser',
                'query_builder' => function (UserRepository $er) use ($options) {
                    return $er->finUserWorkGroupEvent($options['event']);
                },
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GMV\gmvEventBundle\Entity\WorkingGroupProtocol',
            'event' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gmv_gmveventbundle_workinggroupprotocol';
    }


}
