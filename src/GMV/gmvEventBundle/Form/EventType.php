<?php

namespace GMV\gmvEventBundle\Form;


use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;


class EventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'required' => true, 'label' => 'Название мероприятия'
        ))
            ->add('enabled', CheckboxType::class, array(
                'required' => true, 'label' => 'Активность'
            ))

            ->add('description', CKEditorType::class, array(
                'label' => 'Описание',
                'config' => array(
                    'filebrowserBrowseRoute' => 'elfinder',
                    'filebrowserBrowseRouteParameters' => array(
                        'instance' => 'default',
                        'homeFolder' => $options['event_home_folder'])
                )))
            ->add('phone', PhoneNumberType::class,array(
                'label' => 'Телефон',
                'widget' => PhoneNumberType::WIDGET_COUNTRY_CHOICE,
                'country_choices' => array('BY', 'RU', 'UA', 'US'),
                'preferred_country_choices' => array('GB', 'JE')))

            ->add('phoneNumberTwo', PhoneNumberType::class,array(
                'label' => 'Дополнительный телефон',
                'widget' => PhoneNumberType::WIDGET_COUNTRY_CHOICE,
                'country_choices' => array('BY', 'RU', 'UA', 'US'),
                'preferred_country_choices' => array('GB', 'JE')))

            ->add('theContactPerson', TextType::class, array(
                'required' => true, 'label' => 'Лицо для связи'
            ))
            ->add('site', UrlType::class, array(
                'required' => true, 'label' => 'Сайт'
            ))
            ->add('address', TextType::class, array(
                'required' => true, 'label' => 'Адрес мероприятия'
            ))
             ->add('date_start', DateType::class, array(
                'widget' => 'single_text','html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'label' => 'Дата начала мероприятия'))
             ->add('date_finish', DateType::class, array(
                'widget' => 'single_text','html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'label' => 'Дата окончания мероприятия'))

            ->add('time_start', TimeType::class, array(
                'placeholder' => 'Выбрать', 'label' => 'Время начала мероприятия'))
            ->add('time_finish', TimeType::class, array(
                'placeholder' => 'Выбрать', 'label' => 'Время окончания мероприятия'))

            ->add('agelimit', NumberType::class, array(
                 'label' => 'Возрастное ограничение'))

            ->add('typeActivityEvent', EntityType ::class, array(
                'class' => 'EventBundle:TypeActivityEvents',
                'label' => 'Тип проведения мероприятия'))
            ->add('theTargetAudienceEvents', EntityType ::class, array(
                'class' => 'EventBundle:TheTargetAudienceEvents',
                'label' => 'Целевая аудитория'))
            ->add('mainPurposeTheEvent', EntityType ::class, array(
                    'class' => 'EventBundle:MainPurposeTheEvent',
                    'label' => 'Главная цель мероприятия'))
            ->add('typeEvents', EntityType ::class, array(
                    'class' => 'EventBundle:TypeEvents',
                    'label' => 'Вид мероприятия'))
            ->add('coordinates', HiddenType::class, array('label' => 'Координаты')
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GMV\gmvEventBundle\Entity\Event',
            'event_home_folder' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gmv_gmveventbundle_event';
    }


}
