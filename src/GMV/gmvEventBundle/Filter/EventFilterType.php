<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 14.11.2017
 * Time: 19:10
 */

namespace GMV\gmvEventBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;

class EventFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('GET');
        $builder->add('name', Filters\TextFilterType::class, [
            'label' => 'Название мероприятия'
        ])
            ->add('id', Filters\NumberFilterType::class)
            ->add('date_start', Filters\DateFilterType::class, [
                'widget' => 'single_text', 'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'label' => 'Дата начала мероприятия',
            ])
            ->add('date_finish', Filters\DateFilterType::class, [
                'widget' => 'single_text', 'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'label' => 'Дата окончания мероприятия',
            ])
            ->add('typeEvents', Filters\EntityFilterType ::class, array(
                'class' => 'EventBundle:TypeEvents',
                'label' => 'Вид мероприятия'))
            ->add('category', Filters\EntityFilterType::class, [
                'class' => 'EventBundle:TypeActivityEvents',
                 'label' => 'Тип проведения мероприятия',
            ]);
    }

    public function getBlockPrefix()
    {
        return 'event_filter';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}