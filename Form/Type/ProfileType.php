<?php

namespace Bkstg\CoreBundle\Form\Type;

use MidnightLuke\PhpUnitsOfMeasureBundle\Form\Type\LengthType;
use MidnightLuke\PhpUnitsOfMeasureBundle\Form\Type\MassType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', MediaType::class, [
                'provider' => 'sonata.media.provider.image',
                'context' => 'default',
            ])
            ->add('email', EmailType::class)
            ->add('phone')
            ->add('height', LengthType::class)
            ->add('weight', MassType::class)
            ->add('facebook', UrlType::class)
            ->add('twitter', UrlType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bkstg\CoreBundle\Entity\Profile'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bkstg_corebundle_profile';
    }
}
