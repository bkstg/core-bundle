<?php

namespace Bkstg\CoreBundle\Form\Type;

use Bkstg\CoreBundle\Entity\Production;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductionSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', MediaType::class, [
                 'provider' => 'sonata.media.provider.image',
                 'context'  => 'default'
            ])
            ->add('description', CKEditorType::class, [
                'required' => false,
                'config' => ['toolbar' => 'basic'],
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
