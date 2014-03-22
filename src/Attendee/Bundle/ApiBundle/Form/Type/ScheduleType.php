<?php

namespace Attendee\Bundle\ApiBundle\Form\Type;

use Attendee\Bundle\ApiBundle\Form\Transformer\RRuleTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class ScheduleType
 *
 * @package Attendee\Bundle\ApiBundle\Form\Type
 *
 * @DI\FormType
 */
class ScheduleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('startsAt', 'datetime', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
            ));

        $transformer = new RRuleTransformer();

        $builder
            ->add(
                $builder
                    ->create('rRule', 'text')
                    ->addModelTransformer($transformer)
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'Attendee\Bundle\ApiBundle\Entity\Schedule',
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'schedule';
    }
}
