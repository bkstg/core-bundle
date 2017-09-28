<?php

namespace Bkstg\CoreBundle\Form\Type;

use Bkstg\CoreBundle\Entity\User;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('enabled', BooleanType::class, [
                'data' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'required' => false,
                'choices' => [
                    'Editor' => 'ROLE_EDITOR',
                    'Admin' => 'ROLE_ADMIN',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
