<?php

namespace Bkstg\CoreBundle\Form\Type;

use Bkstg\CoreBundle\BkstgCoreBundle;
use Bkstg\CoreBundle\Entity\Production;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
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
            ->add('name', TextType::class, [
                'label' => 'production.form.name',
            ])
            ->add('image', MediaType::class, [
                'label' => 'production.form.image',
                 'provider' => 'sonata.media.provider.image',
                 'context'  => 'default',
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'production.form.description',
                'required' => false,
                'config' => ['toolbar' => 'basic'],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'production.form.status',
                'choices' => [
                    'production.form.status_choices.active' => Production::STATUS_ACTIVE,
                    'production.form.status_choices.closed' => Production::STATUS_CLOSED,
                ],
            ])
            ->add('visibility', ChoiceType::class, [
                'label' => 'production.form.visibility',
                'choices' => [
                    'production.form.visibility_choices.public' => Production::VISIBILITY_PUBLIC,
                    'production.form.visibility_choices.private' => Production::VISIBILITY_PRIVATE,
                ],
            ])
            ->add('expiry', DateTimeType::class, [
                'label' => 'production.form.expiry',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
            'data_class' => Production::class,
        ));
    }
}
