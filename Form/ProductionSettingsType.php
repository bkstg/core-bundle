<?php

namespace Bkstg\CoreBundle\Form;

use Bkstg\CoreBundle\BkstgCoreBundle;
use Bkstg\CoreBundle\Entity\Production;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductionSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', MediaType::class, [
                'label' => 'production.form.image',
                'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
                'provider' => 'sonata.media.provider.image',
                'context'  => 'default',
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'production.form.description',
                'required' => false,
                'config' => ['toolbar' => 'basic'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
            'data_class' => Production::class,
        ]);
    }
}
