<?php

namespace Bkstg\CoreBundle\Form\Type;

use Bkstg\CoreBundle\Model\Production;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('image', MediaType::class, [
                 'provider' => 'sonata.media.provider.image',
                 'context'  => 'default'
            ])
            ->add('description', CKEditorType::class, [
                'required' => false,
                'config' => ['toolbar' => 'basic'],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Active' => Production::STATUS_ACTIVE,
                    'Closed' => Production::STATUS_CLOSED,
                ],
            ])
            ->add('visibility', ChoiceType::class, [
                'choices' => [
                    'Public' => Production::VISIBILITY_PUBLIC,
                    'Private' => Production::VISIBILITY_PRIVATE,
                ],
            ])
            ->add('expiry', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Production::class,
        ));
    }
}
