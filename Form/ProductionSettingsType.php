<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Form;

use Bkstg\CoreBundle\BkstgCoreBundle;
use Bkstg\CoreBundle\Entity\Production;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductionSettingsType extends AbstractType
{
    /**
     * Build the production settings form.
     *
     * @param FormBuilderInterface $builder The form builder.
     * @param array                $options The options.
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', MediaType::class, [
                'label' => 'production.form.image',
                'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
                'provider' => 'sonata.media.provider.image',
                'context' => 'default',
            ])
            ->add('banner', MediaType::class, [
                'label' => 'production.form.banner',
                'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
                'provider' => 'sonata.media.provider.image',
                'context' => 'default',
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'production.form.description',
                'required' => false,
                'config' => ['toolbar' => 'basic'],
            ])
        ;
    }

    /**
     * Define the default options for the form.
     *
     * @param OptionsResolver $resolver The options resolver.
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => BkstgCoreBundle::TRANSLATION_DOMAIN,
            'data_class' => Production::class,
        ]);
    }
}
