<?php

namespace Bkstg\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Bkstg\CoreBundle\Form\PositionType;

class UserType extends BaseType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('username')
            ->remove('plainPassword')
            ->add('firstName')
            ->add('lastName')
            ->add('phoneHome')
            ->add('phoneMobile')
            // ->add('file')
            ->add('roles', 'choice', array(
                'choices' => array(
                    'ROLE_EDITOR' => 'Editor',
                    'ROLE_ADMIN' => 'Administrator',
                ),
                'multiple' => true,
                'expanded' => true,
            ))
            ->add('positions', 'collection', array(
                'type' => new PositionType(),
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bkstg\CoreBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bkstgcorebundle_user';
    }
}
