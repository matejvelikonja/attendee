<?php

namespace Attendee\Bundle\ApiBundle\Form\Type;

use Attendee\Bundle\ApiBundle\Form\Transformer\UsersTransformer;
use Attendee\Bundle\ApiBundle\Service\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class TeamType
 *
 * @package Attendee\Bundle\ApiBundle\Form\Type
 *
 * @DI\FormType
 */
class TeamType extends AbstractType
{
    /**
     * @var \Attendee\Bundle\ApiBundle\Service\UserService
     */
    private $service;

    /**
     * @param UserService $service
     *
     * @DI\InjectParams({
     *      "service" = @DI\Inject("attendee.user_service")
     * })
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new UsersTransformer($this->service);

        $builder
            ->add('name')
            ->add(
                $builder
                    ->create('users', 'text')
                    ->addModelTransformer($transformer)
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'Attendee\Bundle\ApiBundle\Entity\Team',
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'team';
    }
}
