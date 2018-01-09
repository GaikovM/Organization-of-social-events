<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 05.11.2017
 * Time: 23:00
 */

namespace GMV\gmvEventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EventViewShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['ImAMember'] == false) {
            $builder->add('LeaveEventUser', SubmitType::class, array('label' => 'Покинуть мероприятие'));
        } elseif ($options['ImAMember'] == true) {
            $builder->add('BecomeEventUser', SubmitType::class, array('label' => 'Стать участником'));
        }
    }


    public
    function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GMV\gmvEventBundle\Entity\Event',
            'ImAMember' => null,
        ));
    }

    public
    function getBlockPrefix()
    {
        return parent::getBlockPrefix();
    }


}